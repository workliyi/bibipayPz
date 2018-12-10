<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/19 0019
 * Time: 19:13
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\WithdrawalsApply as WithdrawalsApplyModel;


class WithdrawalsApply extends Controller
{
    //提现申请审核列表
    public function lists(Request $request, TokenModel $TokenModel)
    {
        $token_symbol = $request->post('token_symbol');
        $status = $request->post('status');
        $perPage = $request->query('perPage', 15);
        $builder = with(new WithdrawalsApplyModel())->setHidden([])->newQuery();
        $condition = [];
        if ($token_symbol !== 'all' && !empty($token_symbol)) {
            $condition['type'] = $token_symbol;
        }
        if ($status !== 'all'){
            $condition['status'] = $status;
        }
        foreach ($condition as $key => $value) {
            if (!is_null($value)) {
                if ($key == 'status' && $value == 1) {
                    $builder->whereIn('status', [1, 2]);
                } else {
                    $builder->where($key, '=', $value);
                }
            }
        }
        $data = $builder->orderBy('created_time', 'desc')->paginate(15);

        //返回币种提现手续费
        $poundage = $TokenModel->select('id', 'token_name', 'poundage')->get();
        return response()->json([
            'data' => $data,
            'poundage' => $poundage,
//            'code' => 200,
//            'message' => '成功'
        ]);
    }

    //提现审核操作(通过/拒绝)
    public function examine(Request $request, WithdrawalsApplyModel $WithdrawalsApplyModel)
    {
        $data = $request->all();
        $result = $WithdrawalsApplyModel->where('id', $data['id'])->update(['status' => $data['status']]);
        $user = BaseUserModel::where('id', $WithdrawalsApplyModel->where('id', $data['id'])->value('user_id'))
            ->first();
        if ($result) {
            if ($data['status'] == 1 || $data['status'] == 2) {
//                $return = (new QclodeController())->send('尔尔', '15245161417', 202101);
                $return = (new QclodeController())->send($user['name'], $user['tel'], 202101);
                return $return;
            }
            if ($data['status'] == 3) {
//                $return = (new QclodeController())->send('王山', '15245161417', 202105);
                $return = (new QclodeController())->send($user['name'], $user['tel'], 206109);
                return $return;
            }
            return response()->json(['id' => $data['id'], 'code' => 200, 'message' => '成功']);
        }
    }
}