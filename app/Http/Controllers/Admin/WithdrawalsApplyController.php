<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 19:13
 */

namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Curl as Curl;


class WithdrawalsApplyController extends Controller
{
    const URL_APPLY_DETAIL = 'http://192.168.1.14:8787/api/openadmin/getapplylist';//提现申请审核列表
    const URL_EXAMINE_DETAIL = 'http://192.168.1.14:8787/api/openadmin/examine';//提现审核操作(通过/拒绝)
    //提现申请审核列表
    public function lists(Request $request , Curl $curl)
    {
        $token_symbol = $request->token_symbol;
        $status = $request->status;
        $get_apply_list = $curl->curl(WithdrawalsApplyController::URL_APPLY_DETAIL , ['token_symbol'=> $token_symbol , 'status'=>$status] , 1);
        $get_apply_list = json_decode($get_apply_list , true);
        return $get_apply_list;
    }
    //提现审核操作(通过/拒绝)
    public function examine(Request $request, Curl $curl)
    {
        $id = $request->id;
        $status = $request->status;
        $set_examine = $curl->curl(WithdrawalsApplyController::URL_EXAMINE_DETAIL , ['id'=>$id,'status'=>$status] , 1);
        $set_examine = json_decode($set_examine , true);
        return $set_examine;
    }
}