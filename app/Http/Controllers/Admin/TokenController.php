<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 15:02
 */

namespace App\Http\Controllers\Admin;

use App\Model\Curl as Curl;
use App\Model\AuthCodeKey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Token as TokenModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
class TokenController extends Controller
{
    const URL_WALLET_DETAIL = 'http://0.0.0.0:8787/api/openadmin/setToken';//获取钱包信息
    const URL_USDT_DETAIL = 'http://0.0.0.0:8787/api/openadmin/usdtdet';//获取usdt总账信息
    //返回权证币种
    public function return_token(TokenModel $TokenModel)
    {
        $data = $TokenModel->select('token_name', 'id', 'poundage', 'status')->get();
        return response()->json($data);
    }
    //设置币种是否开启提现审核
    public function setting(Request $request, TokenModel $TokenModel)
    {
        $data = $request->TokenModel;
        foreach ($data as $value) {
            $TokenModel->where('id', $value['id'])->update(['status' => $value['status']]);
        }
        return response()->json(['message' => '成功', 'code' => 200, 'data' => $data]);
    }
    //设置提现手续费
    public function poundage(Request $request, TokenModel $TokenModel,Curl $curl, ResponseContract $response)
    {
        $data = $request->post();
        $get_token_detail = $curl->curl(TokenController::URL_WALLET_DETAIL , ['token_name'=>$data['token_name'],'poundage'=>$data['poundage']] , 1);
        $get_token_detail = json_decode($get_token_detail , true);
        if($get_token_detail['code'] == 200){
            $result = $TokenModel
            ->where(['id' => $data['id'], 'token_name' => $data['token_name']])
            ->update(['poundage' => $data['poundage']]);
            if ($result) {
                return $response->json(['id' => $data['id'], 'code' => 200, 'message' => '成功']);
            }
        }
        return $response->json(['id' => $data['id'], 'code' => 40052, 'message' => '修改失败']);
    }
    //获取usdt详细信息
    public function getusdt(Request $request, TokenModel $TokenModel,Curl $curl, ResponseContract $response)
    {

        $get_usdt_detail = $curl->curl(TokenController::URL_USDT_DETAIL , [] , 1);
        $get_usdt_detail = json_decode($get_usdt_detail , true);
        return $get_usdt_detail;
    }
}