<?php

namespace App\Http\Controllers\APIS;

use App\Model\User;
use App\Model\Curl;
use App\Model\QzOrder;
use App\Model\QzProduct;
use App\Model\QzChargeLog;
use App\Model\AuthCodeKey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class QzBuyController extends Controller
{
    /**
     * @param Request $request
     * @param ResponseContract $response
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null|object|static
     *
     *
     * 回复码：
     *     40010：已到达最大订购量
     *     40020：余额不足
     *	   40030：交易已结束
     *     40024：钱包操作失败
     */
    const URL_WALLET_DETAIL = UCENTER_URL.'/api/open/walletdetail';//获取钱包信息
    const URL_MODIFY_WALLET = UCENTER_URL.'/api/open/modwallet';//修改钱包余额等
    const URL_SET_CHARGE_LOG = UCENTER_URL.'/api/open/chargelog';//记录用户钱包操作
    //权证购买接口
    public function buyGoods(Request $request, ResponseContract $response ,QzOrder $order,Curl $curl, AuthCodeKey $getkey)
    {
        //获取用户信息
        $user = $request->user;
        $product_id = $request->input('id');
        $product_name = $request->input('title');
        $token_amount = $request->input('token_amount');  //数量
        $token_name = 'USDT';
        $token_price = $request->input('token_price');  //单价
        $all_money = $token_price*$token_amount;
        $product = QzProduct::where('id' , $product_id)->first();
        //获取平台密钥
        $plat_key = config('app.plat_key');
        $plat_num = config('app.plat_num');
        //查询用户
        $base_user = User::where('id' , $user->id)->first();
        //加密用户密钥
        $plat_sec_key = $getkey->authcode($user->key,'ENCODE',$plat_key,0);
        $get_token_detail = $curl->curl(QzBuyController::URL_WALLET_DETAIL , ['tokentype'=>$token_name,'platnum'=>$plat_num,'platkey'=>$plat_sec_key] , 1);
        $get_token_detail = json_decode($get_token_detail);
        //判断是否超过过期时间
        if(time() > $product->end_time){
            return $response->json(['message' => '交易已结束','code' => '40030']);
        }
        //查看是否超过购买限制
        $buyRestrict = $this->doRestrict($user->id , $product_id , $token_amount);
        if ($buyRestrict == false){
            return $response->json(['message' => '已到达最大订购量','code' => '40010']);
        }
        //判断用户入口
        if(empty($request->input('door_type'))){
            //判断用户余额是否充足
            if(!empty($get_token_detail)) {
                if($get_token_detail->balance < $all_money){
                    //创建未付款订单
                    $order_data = [
                        'user_id' => $user->id,
                        'product_id' => $product_id,
                        'product_name' =>$product_name,
                        'status' => 0,
                        'token_price' => $token_price,
                        'token_amount' => $token_amount,
                        'token_type' => $get_token_detail->token_type,
                        'create_time' => time(),
                        'buy_time' =>time(),
                        'exercise_time' => $product->exercise_end_time
                    ];
                    $orderId = $order->insertGetId($order_data);
                    return $response->json(['message' => '余额不足','code' => '40020']);
                }
            }
        }
        if($get_token_detail->balance < $all_money){
            return $response->json(['message' => '余额不足','code' => '40020']);
        }
        //创建已付款订单
        $order_data = [
            'user_id' => $user->id,
            'product_id' => $product_id,
            'product_name' =>$product_name,
            'status' => 1,
            'token_price' => $token_price,
            'token_amount' => $token_amount,
            'token_type' => $get_token_detail->token_type,
            'create_time' => time(),
            'buy_time' =>time(),
            'exercise_time' => $product->exercise_end_time
        ];
        $orderId = $order->insertGetId($order_data);
        //创建账户流水记录
        $log_data = [
            'user_id' => $base_user->id,
            'less_number' => $all_money,
            'type' => $get_token_detail->token_type,
            'created_time' => date('Y-m-d H:i:s' , time()),
            'action_type' => 7,
            'category' => 0
        ];
        QzChargeLog::insertGetId($log_data);
        //请求用户中心接口处理钱包余额
        $token_data = [
            'balance' => $get_token_detail->balance - $all_money,
            'total_expenses' => $get_token_detail->total_expenses + $all_money,
            'platnum'=>$plat_num,
            'platkey'=>$plat_sec_key,
            'tokentype' => $get_token_detail->token_type,
            'user_id' => $base_user->id,
            'less_number' => $all_money,
            'created_time' => date('Y-m-d H:i:s' , time()),
            'action_type' => 7,
            'category' => 2
        ];
        $modify_detail = $curl->curl(QzBuyController::URL_MODIFY_WALLET , $token_data , 1);
        $modify_detail = json_decode($modify_detail,true);
        if ($modify_detail['code'] == 40024){
            return $response->json(['message' => '操作失败','code' => '40024']);
        }
        $data = [
            'title' => $product->title,
            'exercise_time' => date('Y-m-d H:i:s' , $product->exercise)
        ];
        return $response->json(['message' => $data,'code' => '200']);
    }
    //获取充值token地址
    public function getAddress(Request $request , ResponseContract $response){
        $user = $request->user();
        //查询用户
        $base_user = User::where('sns_uid' , $user->id)->first();
        $token_address = UserAddress::where('user_id' , $base_user->id)->where('status' , 1)->first();
        return $response->json($token_address->usdt_path);
    }
    //判断用户是否超过购买限制
    protected function doRestrict($user_id , $product_id ,$token_amount){
        $product = QzProduct::where('id' , $product_id)->first();
        $max_num_option = $product->max_num_option;
        $allOrder = QzOrder::where('user_id' , $user_id)
            ->whereIn('status' , [1, 2, 4])
            ->where('product_id' , $product_id)->sum('token_amount');
        $all_amount = $allOrder+$token_amount;
        if ($max_num_option >= $all_amount) {
            return true;
        }
        return false;
    }
}
