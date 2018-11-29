<?php

namespace App\Http\Controllers\APIS;

use App\Model\User;
use App\Model\Curl;
use App\Model\AuthCodeKey;
use App\Model\QzProduct;
use App\Model\QzBanner;
use App\Model\QzOkdata;
use App\Model\QzRmb;
use App\Model\QzOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class QzHomeController extends Controller
{
    const USER_URL= 'http://0.0.0.0:8787/api/users/getuser';//获取用户信息
    const WALLET_URL = 'http://0.0.0.0:8787/api/open/setwallet';//创建用户钱包
    public function index(Request $request , User $users ,ResponseContract $response, Curl $curl , AuthCodeKey $getkey){
        //获取用户密钥
        $user = $request->user;
        //获取平台密钥
        $plat_key = config('app.plat_key');
        //获取平台编号
        $plat_num = config('app.plat_num');
        //没有则记录用户
        if (!$user){
            //获取用户邀请码
            $send_code = $request->send_code;
            //验证用户邀请码
            $isset_code = User::where('my_code' , $send_code)->first();
            if(empty($isset_code)){
                return $response->json(['message' => ['邀请码不存在']], 422);
            }
            //生成用户邀请码
            $my_code = str_shuffle(substr(uniqid(),3,6));
            //验证是否已经存在
            $isset_mycode =User::where('my_code' , $my_code)->first();
            if(!empty($isset_mycode)){
                $my_code = str_shuffle(substr(uniqid(),3,6));
            }
            //加密用户密钥
            $plat_sec_key = $getkey->authcode($user->key,'ENCODE',$plat_key,0);
            //获取用户信息
            $get_user_detail = $curl->curl(QzHomeController::USER_URL , ['platnum' =>$plat_num , 'platekey'=>$plat_sec_key] , 1);
            $user_detail = json_decode($get_user_detail);
            $user_data = [
                'name' => $user_detail->name,
                'key' => $user_detail->key,
                'tel' => $user_detail->tel,
                'email' => $user_detail->email,
                'auth' => $user_detail->auth,
                'type' => $user_detail->type,
                'created_at' => $user_detail->created_at,
                'updated_at' => $user_detail->updated_at,
                'short_message_number' => $user_detail->short_message_number,
                'my_code' => $my_code,
                'send_code'=>$send_code
            ];
            $user_id = $users->insertGetId($user_data);
        }

        //加密用户密钥
        //$pla_sec_key = $getkey->authcode($plat_key,'ENCODE',$key,0);
        $plat_sec_key = $getkey->authcode($user->key,'ENCODE',$plat_key,0);
        //初始化权证用户钱包
        $get_wallet = $curl->curl(QzHomeController::WALLET_URL , ['platnum' =>$plat_num , 'platkey'=>$plat_sec_key , 'tokentype'=>'USDT'] , 1);

        //获取首页banner信息
        $banner = QzBanner::get()->toArray();
        //获取ipc实时价格
        $now_ipc_price['price'] = $this->getIpc();

        //获取期权信息
        $option = QzProduct::orderBy('pay_end_time', 'desc')->where('withdraw' , 2)->get()->toArray();
        $getOption = $this->doOption($option);
        $return = array_merge($getOption,$banner,$now_ipc_price);
        $return['token'] = $request->basetoken;
        return $response->json($return);
    }
    //用户订单详情
    public function orderDetail(Request $request, ResponseContract $response){
        //获取产品id
        $order_id = $request->input('id');
        $option = QzOrder::where('qz_order.id' , $order_id)
            ->select('qz_order.status','qz_product.title','qz_order.product_id','qz_order.token_amount','qz_order.buy_time','qz_product.description','qz_product.exercise_start_time','qz_product.exercise_end_time')
            ->leftJoin('qz_product' , 'qz_product.id' , '=' , 'qz_order.product_id')->get();
        return $response->json($option);
    }
    //用户购买产品详情
    public function productDetail(Request $request, ResponseContract $response){
        //获取产品id
        $product_id = $request->input('id');
        $option = QzProduct::where('id' , $product_id)->get();
        //return $response->json($option);
        $token_price = $this->price($product_id);
        //获取产品单价
        $option[0]['token_price'] = $token_price;
        return $response->json($option);
    }
    //计算合约单价
    public function price($id){
        //获取后台提交的产品信息
        $proportion = QzProduct::where('id' , $id)->first();
        //获取后台发行价格
        $ipc = $proportion->issue_price;
        //获取每份产品基数（张）
        //$num = $proportion->min_number*1000;
        $num = 1000;
        //获取期权比例
        $first_num = $proportion->contract_first;
        $second_num = $proportion->constarct_second;
        //return $num;
        //得到对应一份期权对应的ipc
        $ipc_price = ($second_num/$first_num)*$ipc*$num;
        //换算成对应的usdt
        $usdt = $ipc_price/($this->exchangRate());
        return $usdt;
    }
    //处理期权信息
    protected function doOption($option){
        $now = [];
        foreach ($option as $val) {
            $now['option'][] = $val;
        }
        return $now;
    }
    //获取ipc实时价格
    protected function getIpc(){
        $ok_detail = QzOkdata::orderBy('id', 'desc')->limit(1)->first();
        $ipc_price = $ok_detail->last;
        $rmb_ipc = $ipc_price*$this->exchangRate();
        return $rmb_ipc;
    }
    //获取usdt与ipc兑换比例（即美元兑换人民币汇率）
    protected function exchangRate(){
        $price = QzRmb::select('price')->orderBy('create_time' , 'desc')->first();
        $usdtExecut = $price->price;
        return $usdtExecut;
    }

}
