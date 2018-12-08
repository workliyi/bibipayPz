<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 17:29
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ChargeLog as ChargeLogModel;
use App\Http\Controllers\Controller;
use App\Model\ChargeLog;
use App\Model\User as UserModel;
use App\Model\Token as TokenModel;

class BaseUser extends Controller
{
    /**
     * 权证用户账户管理(列表)
     */
    public function account(Request $request, BaseUserModel $UserModel)
    {
        $userId = $request->post('userId');
        $name = $request->post('name');
        $perPage = $request->post('perPage', 15);
        if ($userId && $users = $UserModel->where(['id' => $userId])
                ->paginate($perPage)) {
            $datas = BaseUserModel::where(['id' => $userId])->orderBy('id', 'desc')->paginate(15);
            $datas->each(function ($item) {
                $item['wallet'] = TokenWallet::where('user_id', $item['id'])
                    ->select('balance', 'type_name')
                    ->get();
                return $item;
            });
            return response()->json($datas)->setStatusCode(200);
        }
        if ($name) {
            $datas = BaseUserModel::where('name' ,'like', '%'.$name.'%')
                ->orderBy('id', 'desc')->paginate(15);
            $datas->each(function ($item) {
                $item['wallet'] = TokenWallet::where('user_id', $item['id'])
                    ->select('balance', 'type_name')
                    ->get();
                return $item;
            });
            return response()->json($datas)->setStatusCode(200);
        }
//        $datas = array_map(function ($data){
//            $data['wallet'] = TokenWallet::where('user_id', $data['id'])
//                ->select('balance', 'type_name')
//                ->get();
//            return $data;
//        }, array(BaseUserModel::where(['platform' => 1])->orderBy('id', 'desc')->paginate(2)));

        $datas = BaseUserModel::orderBy('id', 'desc')->paginate(15);
        $datas->each(function ($item) {
            $item['wallet'] = TokenWallet::where('user_id', $item['id'])
                ->select('balance', 'type_name')
                ->get();
            return $item;
        });
        return response()->json($datas)->setStatusCode(200);
    }


    /**
     * 行权用户收支记录
     */
    public function account_log(Request $request, ChargeLogModel $ChargeLogModel)
    {

        $userid = $request->post('user_id');
        $data = $ChargeLogModel
            ->where('user_id', $userid)
            ->whereIn('action_type', [5,6,7,8,9,10])->paginate(15);

        $data->each(function ($item) {
            $action = [
                5 => '提现',
                6 => '充值',
                7 => '购买权证产品',
                8 => '行权',
                9 => '行权支出',
                10 => '行权收益',
            ];
            $item['type'] = TokenModel::where('id', $item['type'])
                ->value('token_name');
            $item['action_type'] = $action[$item['action_type']];
            return $item;
        });
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * 权证用户管理(列表)
     */
    public function user_list(Request $request)
    {
        echo $request;
        $beginToday = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d'),date('Y')));
        $endToday = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);

        $id = $request->post('id');//id
        $name = $request->post('name');//用户名称
        $tel = $request->post('tel');//手机号
        $beginTime = $request->post('beginTime');//查询开始时间
        $endTime = $request->post('endTime');//查询结束时间

        $builder = with(new BaseUserModel())->setHidden([])->newQuery();

        //整合筛选条件
        $condition = [
            'id' => [
                'operator' => '=',
//                'value' => sprintf('%%%s%%', $id),
                'value' => $id,
                'condition' => boolval($id),
            ],
            'name' => [
                'operator' => 'like',
                'value' => sprintf('%%%s%%', $name),
                'condition' => boolval($name),
            ],
            'tel' => [
                'operator' => '=',
//                'value' => sprintf('%%%s%%', $tel),
                'value' => $tel,
                'condition' => boolval($tel),
            ],
        ];

        foreach($condition as $key => $item) {
        if ($item['condition']) {
            $builder->where('base_user.' . $key, $item['operator'], $item['value']);
        }
    }
        if ($beginTime && !$endTime) {//只有开始时间
//            $condition['created_at'] = [
//                'operator' => '>',
//                'value' => sprintf('%%%s%%', $beginTime),
//                'condition' => boolval($beginTime),
//            ];
            $data = $builder->leftJoin('users', 'base_user.sns_uid', '=', 'users.id')
                ->where('users.created_at', '>', $beginTime)
                ->select('base_user.id', 'base_user.name', 'base_user.tel', 'base_user.email',
                    'users.created_at')
                ->orderBy('users.created_at', 'desc')->paginate(15);
        } elseif (!$beginTime && $endTime) {//只有结束时间
//            $condition['created_at'] = [
//                'operator' => '<',
//                'value' => sprintf('%%%s%%', $endTime),
//                'condition' => boolval($endTime),
//            ];
            $data = $builder->leftJoin('users', 'base_user.sns_uid', '=', 'users.id')
                ->where('users.created_at', '<', $endTime)
                ->select('base_user.id', 'base_user.name', 'base_user.tel', 'base_user.email',
                    'users.created_at')
                ->orderBy('users.created_at', 'desc')->paginate(15);
        } elseif($beginTime && $endTime) {//筛选时间区间
            $builder->whereBetween('users.created_at', [$beginTime, $endTime]);
            $data = $builder->leftJoin('users', 'base_user.sns_uid', '=', 'users.id')
                ->whereBetween('users.created_at', [$beginTime, $endTime])
                ->select('base_user.id', 'base_user.name', 'base_user.tel', 'base_user.email',
                    'users.created_at')
                ->orderBy('users.created_at', 'desc')->paginate(15);

        } else {
            //查询用户列表
            $data = $builder->leftJoin('users', 'base_user.sns_uid', '=', 'users.id')
                ->select('base_user.id', 'base_user.name', 'base_user.tel', 'base_user.email',
                    'users.created_at')
                ->orderBy('users.created_at', 'desc')->paginate(15);
        }


        //共注册人数
        $login_count = (new BaseUserModel())->count();

        //当日注册人数
        $today_login_count = (new BaseUserModel())
            ->join('users', 'base_user.sns_uid', '=', 'users.id')
            ->whereBetween('users.created_at', [$beginToday, $endToday])
            ->count();

        //共充值usdt
        $USDT_id = (new TokenModel())->where('token_name', 'USDT')->value('id');
        $usdt_count = (new ChargeLogModel())
            ->where(['type' => $USDT_id, 'action_type' => 6])
            ->sum('add_number');

        //今日充值usdt
        $today_usdt_count = (new ChargeLogModel())
            ->where(['type' => $USDT_id, 'action_type' => 6])
            ->whereBetween('created_time', [$beginToday, $endToday])
            ->sum('add_number');
//        $data_array = [];
//        foreach ($data as $val){
//            $data_array[] = $val;
//        }
//        return $data_array;
        return response()
            ->json([
                'data'              =>      $data,
                'login_count'       =>      $login_count,
                'today_login_count' =>      $today_login_count,
                'usdt_count'        =>      $usdt_count,
                'today_usdt_count'  =>      $today_usdt_count
            ])->setStatusCode(200);
    }

    /**
     * 充值usdt列表
     */
    public function usdt_recharge(Request $request)
    {
        $beginToday = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d'),date('Y')));
        $endToday = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);

        $name = $request->post('name');//用户名称
        $beginTime = $request->post('beginTime');//查询开始时间
        $endTime = $request->post('endTime');//查询结束时间

        $builder = with(new ChargeLogModel())->setHidden([])->newQuery();

        //整合筛选条件
        $condition = [];
        if ($beginTime && !$endTime) {//只有开始时间
            $condition['charge_log.created_time'] = [
                'operator' => '>',
                'value' => sprintf('%%%s%%', $beginTime),
                'condition' => boolval($beginTime),
            ];
        }
        if (!$beginTime && $endTime) {//只有结束时间
            $condition['charge_log.created_time'] = [
                'operator' => '<',
                'value' => sprintf('%%%s%%', $endTime),
                'condition' => boolval($endTime),
            ];
        }
        foreach($condition as $key => $item) {
            if ($item['condition']) {
                $builder->where($key, $item['operator'], $item['value']);
            }
        }
        if ($beginTime && $endTime) {//筛选时间区间
            $builder->whereBetween('charge_log.created_time', [$beginTime, $endTime]);
        }

        //充值记录查询
        $USDT_id = (new TokenModel())->where('token_name', 'USDT')->value('id');
        if ($name) {
            $data = $builder
                ->join('base_user', 'charge_log.user_id', '=', 'base_user.id')
                ->where('base_user.name', 'like', '%'.$name.'%')
                ->where(['charge_log.action_type' => 6, 'charge_log.type' => $USDT_id])
                ->select('base_user.id', 'base_user.name', 'charge_log.add_number', 'charge_log.created_time')
                ->orderBy('charge_log.created_time', 'desc')
                ->paginate(15);
        } else {
            $data = $builder
                ->join('base_user', 'charge_log.user_id', '=', 'base_user.id')
                ->where(['charge_log.action_type' => 6, 'charge_log.type' => $USDT_id])
                ->select('base_user.id', 'base_user.name', 'charge_log.add_number', 'charge_log.created_time')
                ->orderBy('charge_log.created_time', 'desc')
                ->paginate(15);
        }

        //共注册人数
        $login_count = (new BaseUserModel())->count();

        //当日注册人数
        $today_login_count = (new BaseUserModel())
            ->join('users', 'base_user.sns_uid', '=', 'users.id')
            ->whereBetween('users.created_at', [$beginToday, $endToday])
            ->count();

        //共充值usdt
        $usdt_count = (new ChargeLogModel())
            ->where(['type' => $USDT_id, 'action_type' => 6])
            ->sum('add_number');

        //今日充值usdt
        $today_usdt_count = (new ChargeLogModel())
            ->where(['type' => $USDT_id, 'action_type' => 6])
            ->whereBetween('created_time', [$beginToday, $endToday])
            ->sum('add_number');

        return response()
            ->json([
                'data'              =>      $data,
                'login_count'       =>      $login_count,
                'today_login_count' =>      $today_login_count,
                'usdt_count'        =>      $usdt_count,
                'today_usdt_count'  =>      $today_usdt_count
            ])->setStatusCode(200);
    }

}