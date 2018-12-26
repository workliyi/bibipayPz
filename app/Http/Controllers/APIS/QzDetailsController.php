<?php

namespace App\Http\Controllers\APIS;

use App\Model\User;
use App\Model\QzRmb;
use App\Model\Curl;
use App\Model\QzOrder;
use App\Model\QzOkdata;
use App\Model\AuthCodeKey;
use App\Model\QzChargeLog;
use App\Model\QzExerDetail;
use App\Model\QzProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class QzDetailsController extends Controller
{
    /**
     * @var null
     * 错误码
     *      code：
     *          40100:订单异常
     *          40101:不在行权时间段
     */
    protected $ch = null;
    protected $jhKey = 'e6b854cef1d9951f3c674539ef0381a8';
    protected $url = 'http://web.juhe.cn:8080/finance/exchange/rmbquot';
    protected $okurl = 'https://www.okb.com/api/v1/ticker.do?symbol=ipc_usdt';
    const URL_WALLET_DETAIL = 'http://0.0.0.0:8787/api/open/walletdetail';//获取钱包信息
    const URL_MODIFY_WALLET = 'http://0.0.0.0:8787/api/open/modwallet';//修改钱包余额等
    const URL_SET_CHARGE_LOG = 'http://0.0.0.0:8787/api/open/chargelog';//记录用户钱包操作
    //获取用户订单信息
    public function index(Request $request, ResponseContract $response , QzOrder $QzOrder)
    {
        //获取用户信息
        $user = $request->user;
        //获取状态值
        $status = $request->input('status');
        //判断查询条件
        if(isset($status)) {
            $pro_order = $QzOrder->where('user_id' , $user->id)
                ->select('qz_product.pay_end_time' , 'qz_product.id')
                ->leftJoin('qz_product' , 'qz_product.id' , '=' , 'qz_order.product_id')
                ->where('qz_order.status' ,0)
                ->distinct()
                ->get();
            foreach ($pro_order as $value){
                if(time() > $value['pay_end_time']){
                    $data = ['status' => 3 , 'update_time' => time()];
                    $QzOrder->where('user_id' , $user->id)->where('status' ,0)->update($data);
                }
            }
            $order = $QzOrder->where('user_id' , $user->id)
                ->select('qz_order.*','qz_exer_detail.go_time' , 'token.token_name' , 'qz_product.exercise_start_time' ,'qz_product.pay_end_time', 'qz_product.exercise_end_time')
                ->leftJoin('token' , 'token.id' , '=' , 'qz_order.token_type')
                ->leftJoin('qz_product' , 'qz_product.id' , '=' , 'qz_order.product_id')
                ->leftJoin('qz_exer_detail' , 'qz_exer_detail.tid' , '=' , 'qz_order.id')
                ->where('product_type' , 0)->orderBy('qz_order.create_time', 'desc')
                ->where('qz_order.status' ,$status)
                ->get();
            return $response->json($order);
        }
        //获取全部用户订单
        $all_order = $QzOrder->where('user_id' , $user->id)
            ->select('qz_order.*' ,'qz_exer_detail.go_time' , 'token.token_name' , 'qz_product.exercise_start_time' , 'qz_product.exercise_end_time','qz_product.pay_end_time')
            ->leftJoin('token' , 'token.id' , '=' , 'qz_order.token_type')
            ->leftJoin('qz_product' , 'qz_product.id' , '=' , 'qz_order.product_id')
            ->leftJoin('qz_exer_detail' , 'qz_exer_detail.tid' , '=' , 'qz_order.id')
            ->where('product_type' , 0)->orderBy('qz_order.create_time', 'desc')->get();
        return $response->json($all_order);
    }
    //获取用户行权相关数据信息
    public function exerciseDetial(Request $request, ResponseContract $response){
        //获取ipc的价格
        $ipc = $this->getIpc();
        //获取订单id
        $order_id = $request->id;
        //获取已经支付的usdt价格
        $usdt = $request->usdt;
        //获取产品id
        $order = QzOrder::where('id' , $order_id)->first();
        $product_id = $order->product_id;
        //获取行权价格
        $product_detail = QzProduct::where('id' , $product_id)->first();
        $exercise_price = $product_detail->exercise_price;
        if($ipc < $exercise_price){
            //获取用户定金
            $deposit = $order->token_price*$order->token_amount;
            //用户定金直接兑换
            $all_ipc = $deposit/$this->avgPrice($product_id);
            return $response->json(['status' => 0,'ipc' => $ipc ,'usdt' => null ,'exercise_price' =>$exercise_price, 'exchange_ipc' => $all_ipc,'order_id' => $order_id]);
        }
        //获取行权需要usdt差价
        $pay_usdt = $this->price($product_id,$order->token_amount);
        $pay_this = $pay_usdt-$usdt;
        //可兑换ipc价格
        $token_amount = $order->token_amount;
        //$exchange_ipc = $request->input('exchange_ipc');
        $exchange_ipc = $token_amount*1000;
        //$exchange_ipc = $pay_this/$this->avgPrice($product_id);
        return $response->json(['status' => 1,'ipc' => $ipc , 'usdt' => $pay_this ,'exercise_price' =>$exercise_price, 'exchange_ipc' => $exchange_ipc,'order_id' => $order_id]);
    }
    //取消订单
    public function delOrder(Request $request, ResponseContract $response , QzOrder $QzOrder)
    {
        $user = $request->user;
        $product_id = $request->input('product_id');
        $id = $request->id;
        $data = ['status' => 3 , 'update_time' => time()];
        //修改订单为取消状态
        $QzOrder->where('id' , $id)->where('user_id' , $user->id)->where('product_id' , $product_id)->update($data);

        return $response->json(['message' => '订单已取消' , 'code' => 200]);
    }
    //行权详情
    public function exe_detail(Request $request, ResponseContract $response){
        $user = $request->user;
        $uid = $user->id;
        $order_id = $request->input('order_id');
        $order_detail = QzOrder::where('id' , $order_id)->first();
        $capital = $order_detail->token_price*$order_detail->token_amount;
        //获取支出信息
        $detail = QzExerDetail::where('uid' , $uid)->where('tid' , $order_id)->first();
        $detail['capital'] = $capital;
        return $response->json($detail);
    }
    //用户行权
    public function execute(Request $request, ResponseContract $response ,AuthCodeKey $getkey,Curl $curl, QzOrder $QzOrder){
        //获取平台密钥
        $plat_key = config('app.plat_key');
        //获取平台编号
        $plat_num = config('app.plat_num');
        $user = $request->user;
        //获取订单信息
        $id = $request->input('order_id');
        $order = $QzOrder->where('id' , $id)->first();
        if(empty($order->product_id)){
            return $response->json(['message' => '订单异常' , 'code' => 40100]);
        }
        $product_id = $order->product_id;
        //判断是否在行权时间范围
        $goTime = $this->rightTime($product_id);
        if($goTime == false){
            return $response->json(['message' => '不在行权时间段' , 'code' => 40101]);
        }

        $start = date('Y-m-d H:i:s' , $goTime['start']);
        $end = date('Y-m-d H:i:s' , $goTime['end']);
        //获取okex当前ipc价格
        $ipc_price = $this->getIpc();
        $product_detail = QzProduct::where('id' , $product_id)->first();
        //获取产品发行价格
        $issue_price = $product_detail->issue_price;
        //获取用户行权价格
        $exercise_price = $product_detail->exercise_price;
        //加密用户密钥
        $plat_sec_key = $getkey->authcode($user->key,'ENCODE',$plat_key,0);
        //可兑换ipc价格
        $token_amount = $order->token_amount;
        //$exchange_ipc = $request->input('exchange_ipc');
        $exchange_ipc = $token_amount*1000;
        //行权价格低于市场价
        if ($exercise_price < $ipc_price){
            //获取需要补的usdt差价
            $supply = $this->price($product_id , $order->token_amount);
            $pay_money = $supply - $order->token_amount*$order->token_price;
            //计算可兑换的ipc
            $all_ipc = $pay_money/$this->avgPrice($product_id);
            //获取用户钱包信息
            $get_usdt_detail = $curl->curl(QzDetailsController::URL_WALLET_DETAIL , ['tokentype'=>'USDT','platnum'=>$plat_num,'platkey'=>$plat_sec_key] , 1);
            $get_usdt_detail = json_decode($get_usdt_detail);

            //判断用户余额是否充足
            if($get_usdt_detail->balance < $pay_money){
                return $response->json(['message' => '余额不足','code' => '40020']);
            }
            //请求用户中心接口处理usdt钱包余额
            $token_data = [
                'balance' => $get_usdt_detail->balance - $pay_money,
                'total_expenses' => $get_usdt_detail->total_expenses + $pay_money,
                'platnum'=>$plat_num,
                'platkey'=>$plat_sec_key,
                'tokentype' => $get_usdt_detail->token_type,
                'user_id' => $user->id,
                'tid' =>$id,
                'less_number' => $pay_money,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 9,
                'category' => 2
            ];
            $modify_usdt_detail = $curl->curl(QzDetailsController::URL_MODIFY_WALLET , $token_data , 1);
            //创建账户usdt流水记录
            $log_data = [
                'user_id' => $user->id,
                'tid' =>$id,
                'less_number' => $pay_money,
                'type' => 2,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 9,
                'category' => 0,
            ];
            $usdt_goout = QzChargeLog::insertGetId($log_data);
            //获取ipc用户钱包信息
            $get_ipc_detail = $curl->curl(QzDetailsController::URL_WALLET_DETAIL , ['tokentype'=>'IPC','platnum'=>$plat_num,'platkey'=>$plat_sec_key] , 1);
            $get_ipc_detail = json_decode($get_ipc_detail);
            //处理ipc钱包余额
            $token_data = [
                'balance' => $get_ipc_detail->balance - $pay_money,
                'total_expenses' => $get_ipc_detail->total_expenses + $pay_money,
                'platnum'=>$plat_num,
                'platkey'=>$plat_sec_key,
                'tokentype' => $get_ipc_detail->token_type,
                'user_id' => $user->id,
                'tid' =>$id,
                'add_number' => $exchange_ipc,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0
            ];
            $modify_ipc_detail = $curl->curl(QzDetailsController::URL_MODIFY_WALLET , $token_data , 1);
            //创建用户赚取ipc记录
            $ipc_data = [
                'tid' =>$id,
                'user_id' => $user->id,
                'add_number' => $exchange_ipc,
                'type' => 1,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0,
            ];
            $ipc_income = QzChargeLog::insertGetId($ipc_data);
            //处理行权详情
            $exet_data = [
                'uid' =>$user->id,
                'outcome' => $pay_money,
                'income' =>$exchange_ipc,
                'exercise_prise' =>$exercise_price,
                'tid' =>$id,
                'go_time' =>time(),
                'status'=>1
            ];
            QzExerDetail::insertGetId($exet_data);
            //处理订单状态
            $status = QzOrder::where('id' , $id)->update(['status' => 2]);
            //获取返回数据
            if(!$status){
                return $response->json(['message' => '行权失败','code' => '40030']);
            }
            return $response->json(['prise'=>$ipc_price,'usdt' => $pay_money ,
                'ipc' => $all_ipc , 'start' => $start,'end' => $end,'type' =>'0','code' => 200,'message' => '行权成功']);
        } else {
            //行权价格高于市场价格
            //处理订单状态
            QzOrder::where('id' , $id)->update(['status' => 2,'active' => 1]);
            //获取用户定金
            $deposit = $order->token_price*$order->token_amount;
            //用户定金直接兑换
            $all_ipc = $deposit/$this->avgPrice($product_id);
            //获取ipc钱包信息
            $get_ipc_detail = $curl->curl(QzDetailsController::URL_WALLET_DETAIL , ['tokentype'=>'IPC','platnum'=>$plat_num,'platkey'=>$plat_sec_key] , 1);
            $get_ipc_detail = json_decode($get_ipc_detail);
            //用户获取ipc的log记录
            $ipc_data = [
                'tid' =>$id,
                'user_id' => $user->id,
                'add_number' => $all_ipc,
                'type' => 1,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0,
            ];
            $ipc_income = QzChargeLog::insertGetId($ipc_data);
            //处理用户ipc钱包余额
            $token_data = [
                'balance' => $get_ipc_detail->balance + $all_ipc,
                'total_expenses' => $get_ipc_detail->total_income + $all_ipc,
                'platnum'=>$plat_num,
                'platkey'=>$plat_sec_key,
                'tokentype' => $get_ipc_detail->token_type,
                'user_id' => $user->id,
                'tid' =>$id,
                'add_number' => $exchange_ipc,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0
            ];
            $modify_ipc_detail = $curl->curl(QzDetailsController::URL_MODIFY_WALLET , $token_data , 1);
            //处理确权详情表记录
            $log_data = [
                'uid' =>$user->id,
                'outcome' => $deposit,
                'income' =>$all_ipc,
                'exercise_prise' =>$exercise_price,
                'tid' =>$id,
                'go_time' =>time(),
                'status'=>2
            ];
            QzExerDetail::insertGetId($log_data);
            $money = $order->token_amount*$order->token_price;
            return $response->json(['prise'=>$ipc_price,'usdt'=>$money,'exercise_price'=>$exercise_price,
                'ipc' => $all_ipc , 'start' => $start,'end' => $end,'type' =>'0','code' => 200,'message' => '行权成功']);
        }
    }
    //计算合约应支付usdt总额
    public function price($id ,$token_num){
        //获取后台提交的产品信息
        $proportion = QzProduct::where('id' , $id)->first();
        //获取用户行权价格
        $exercise_price = $proportion->exercise_price;
        //获取每份产品基数（张）
        //$num = $proportion->min_number*1000;
        $num = 1000;
        //获取期权比例
        $first_num = $proportion->contract_first;
        $second_num = $proportion->constarct_second;
        //得到对应一份期权对应的ipc
        $ipc_price = ($exercise_price*$num) / $this->getIpc() * $token_num;
        //换算成对应的usdt
        $usdt = $ipc_price*($this->avgPrice($id));
        return $usdt;
    }
    //强制确权
    public function forceExecute(){
        $now = time();
        //获取平台密钥
        $plat_key = config('app.plat_key');
        //获取平台编号
        $plat_num = config('app.plat_num');
        //获取所有需要强制确权的用户
        $all_order = QzOrder::where('status', '=', '1')->where('exercise_time', '<', $now)->get()->toArray();
        
        foreach ($all_order as $order){
            $order_id =  $order['id'];
            //获取用户定金
            $deposit = $order['token_price']*$order['token_amount'];
            //用户定金直接兑换
            $all_ipc = $deposit/$this->avgPrice($order['product_id']);
            //查询用户
            $base_user = User::where('id' , $order['user_id'])->first();
            $getkey = new AuthCodeKey();
            //加密用户密钥
            $plat_sec_key = $getkey->authcode($base_user->key,'ENCODE',$plat_key,0);
            $product_detail = QzProduct::where('id' , $order['product_id'])->first();
            //处理订单状态
            $status = QzOrder::where('id' , $order['id'])->update(['status' => 2]);
            //获取ipc钱包信息
            $get_ipc_detail = Curl::curl(QzDetailsController::URL_WALLET_DETAIL , ['tokentype'=>'IPC','platnum'=>$plat_num,'platkey'=>$plat_sec_key] , 1);
            $get_ipc_detail = json_decode($get_ipc_detail);
            //创建用户赚取ipc记录
            $ipc_data = [
                'tid' =>$order_id,
                'user_id' => $base_user->id,
                'add_number' => $all_ipc,
                'type' => 1,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0,
            ];
            $ipc_income = QzChargeLog::insertGetId($ipc_data);
            //处理用户ipc钱包余额
            $token_data = [
                'balance' => $get_ipc_detail->balance + $all_ipc,
                'total_expenses' => $get_ipc_detail->total_income + $all_ipc,
                'platnum'=>$plat_num,
                'platkey'=>$plat_sec_key,
                'tokentype' => $get_ipc_detail->token_type,
                'user_id' => $base_user->id,
                'tid' =>$order_id,
                'add_number' => $all_ipc,
                'created_time' => date('Y-m-d H:i:s' , time()),
                'action_type' => 10,
                'category' => 0
            ];
            $modify_ipc_detail = Curl::curl(QzDetailsController::URL_MODIFY_WALLET , $token_data , 1);
            //处理确权详情表记录
            $log_data = [
                'uid' =>$base_user->id,
                'outcome' => 0,
                'income' =>$all_ipc,
                'exercise_prise' =>$product_detail->exercise_price,
                'tid' =>$order_id,
                'go_time' =>time(),
                'status' => 2
            ];
            QzExerDetail::insertGetId($log_data);
        }
        $time = date('Y-m-d H:i:s' ,$now);
        if ($all_order){
            return "确权成功.$time";
        } else {
            return "确权失败.$time";
        }
    }
    //获取用户邀请码
    public function getCode(Request $request, ResponseContract $response){
        $user = $request->user;
        $send_code = $user->my_code;
        $username = $user->name;
        return $response->json(['send_code' => $send_code,'name' => $username]);
    }
    //获取ipc的算术平均价
    public function avgPrice($id){
        //获取后台提交的产品信息
        $proportion = QzProduct::where('id' , $id)->first();
        //获取产品交易时间区间
        $pay_start_time = $proportion->pay_start_time;
        $pay_end_time = $proportion->pay_end_time;
        $set_time = $pay_end_time-$pay_start_time;
        if($set_time <= 604800000){
            $ok_detail = QzOkdata::where('create_time' , '<=' , time())->where('type' , 'hours')->orderBy('id', 'desc')->first();
            $avg = ($ok_detail->high + $ok_detail->low)/2;
            return $avg;
        } else {
            $ok_detail = QzOkdata::where('create_time' , '<=' , time())->where('type' , 'hours')->orderBy('id', 'desc')->first();
            $avg = ($ok_detail->high + $ok_detail->low)/2;
            return $avg;
        }
    }
    //美元兑换人民币汇率入库
    public function getRmb(){
        $url = $this->url.'?key='.$this->jhKey.'&type=0&bank=0';
        $getUrl = $this->curlGet($url,'get');
        $arr = json_decode($getUrl,true);
        $resExecute =  $arr['result'][0]['data1']['bankConversionPri'];
        $usdtExecut = $resExecute/100;
        $data = [
            'price' => $usdtExecut,
            'create_time' => time()
        ];
        $id = QzRmb::insertGetId($data);
        Log::info('人民币汇率入库id'.$id);
        return $usdtExecut;
    }
    //获取usdt与ipc兑换比例（即美元兑换人民币汇率）
    protected function exchangRate(){
        $price = QzRmb::select('price')->orderBy('create_time' , 'desc')->first();
        $usdtExecut = $price->price;
        return $usdtExecut;
    }
    //获取OK网动态数据(小时)
    public function getOkdata(){
        $okurl='https://www.okb.com/api/v1/kline.do?symbol=ipc_usdt&type=1hour&size=1';
        $str=$this->get_content($okurl);
        Log::info($str);
        $json=json_decode($str,true);
        if (!empty($json)){
            $data = [
                'high' => $json[0][2],
                'low' =>$json[0][3],
                'last'=>$json[0][1],
                'create_time' => time(),
                'time_date' => date('Y-m-d H:i:s' , time()),
                'type' =>'hours'
            ];
            $id = QzOkdata::insertGetId($data);
            return "ok网动态数据插入成功数据ID：$id";
        } else {
            return "没有获取到数据.$json";
        }
    }

    //curl网络请求
    protected function curlGet($url,$method,$post_data = 0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }elseif($method == 'get'){
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    //curl请求
    protected function get_content($url)
    {
        //$cookie=file_get_contents('cookie.txt');
        $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
        //curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        echo $data;
        return $data;
        //preg_match_all('|value="(.*)"|isU',$data,$arr); //匹配到数组$arr中；
        //return $arr[1][2]; //$arr[1]就是匹配的结果
        //echo curl_errno($curl); //返回0时表示程序执行成功 如何从curl_errno返回值获取错误信息
    }
    //判断是否在行权时间范围
    protected function rightTime($id){
        $product = QzProduct::select('exercise_start_time' , 'exercise_end_time')
            ->where('id' , $id)->first();
        $start = $product->exercise_start_time;
        $end = $product->exercise_end_time;
        $time = ['start'=>$start , 'end' => $end];
        $now = time();
        if($now > $start && $now < $end){
            return $time;
        }
        return false;
    }
    //获取ipc实时价格
    protected function getIpc()
    {
        $ok_detail = QzOkdata::orderBy('id', 'desc')->limit(1)->first();
        $ipc_price = $ok_detail->last;
        $rmb_ipc = $ipc_price * $this->exchangRate();
        return $rmb_ipc;
    }
}
