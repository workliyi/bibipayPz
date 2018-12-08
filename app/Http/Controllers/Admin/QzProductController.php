<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 14:21
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\QzProduct as QzProductModel;
use App\Http\Controllers\Controller;

class QzProductController extends Controller
{
    /**
     * 创建权证产品
     */
    public function create(Request $request, QzProductModel $QzProductModel)
    {
        $data = $request->all()['data'];
        $data['withdraw'] = $request->post('withdraw');
        $data['create_time'] = time();
        $data['status'] = 1;
        $data['exercise_start_time'] = $data['exercise_start_time'] == NULL ? NULL : strtotime($data['exercise_start_time']);
        $data['exercise_end_time'] = $data['exercise_end_time'] == NULL ? NULL : strtotime($data['exercise_end_time']);
        $data['ipo_time'] = $data['ipo_time'] == NULL ? NULL : strtotime($data['ipo_time']);
        $data['end_time'] = $data['end_time'] == NULL ? NULL : strtotime($data['end_time']);
        $data['pay_start_time'] = $data['pay_start_time'] == NULL ? NULL : strtotime($data['pay_start_time']);
        $data['pay_end_time'] = $data['pay_end_time'] == NULL ? NULL : strtotime($data['pay_end_time']);
//        $data['zero_time'] = $data['zero_time'] == NULL ? NULL : strtotime($data['zero_time']);
        //产品信息入库
//        $result = $QzProductModel->save($data);
        if (!empty($data['id'])) {
            //id存在 为更新
            $result = DB::table('qz_product')
                ->where('id', $data['id'])
                ->update($data);
            if ($result) {
                //修改成功后更新撤回状态   不管是否为撤回状态  都更改为正常   在前台显示
//                DB::table('qz_product')
//                    ->where('id', $data['id'])
//                    ->update(['withdraw' => 2]);
                return response()->json(['message' => '修改成功', 'code' => 200, 'id' => $QzProductModel->id]);
            }
            return response()->json(['message' => '修改失败，请稍后重试']);
        } else {
            //新增
            $result = DB::table('qz_product')->insert($data);
            if ($result) {
                return response()->json(['message' => '发布成功', 'code' => 200, 'id' => $QzProductModel->id]);
            }
            return response()->json(['message' => '发布失败，请稍后重试']);

        }
//        return response()->json($data);

    }
    
    //期权产品列表
    public function lists()
    {
        $data = QzProductModel::whereIn('withdraw',[1,2])
            ->orderBy('create_time', 'desc')
            ->select('title', 'end_time', 'create_time', 'id', 'pay_end_time', 'withdraw', 'ipo_time')
            ->paginate(15);
        $data->each(function ($item) {
            //判断期权交易结束时间
            if ($item['pay_end_time'] > time()) {//进行中
                $item['end_status'] = 1;
            } elseif ($item['pay_end_time'] < time()) {//已完成
                $item['end_status'] = 2;
            }
            //判断上市时间
            if ($item['ipo_time'] > time()) {//未开始
                $item['end_status'] = 3;
            }
            return $item;
        });
        return response()->json($data)->setStatusCode(200);
    }

    //查看权证产品
    public function data(Request $request, QzProductModel $QzProductModel)
    {
        $id = $request->post('id');
        $data = $QzProductModel->where('id', $id)->first();
        return response()->json($data)->setStatusCode(200);
    }


    //权证产品撤回 (更改withdraw字段：1=>已撤回  2=>正常)
    public function withdraw(Request $request, QzProductModel $QzProductModel)
    {
        $id = $request->post('id');
        $data = $QzProductModel->where('id', $id)->update(['withdraw' => 1]);
        if ($data) {
            return response()->json(['code' => 200]);
        } else {
            return response()->json(['code' => 433]);
        }
    }

    //草稿箱
    public function draft()
    {
        $data = QzProductModel::where('withdraw', 3)
            ->orderBy('create_time', 'desc')
            ->select('title', 'end_time', 'create_time', 'id', 'pay_end_time', 'withdraw', 'ipo_time')
            ->paginate(15);
        return response()->json($data)->setStatusCode(200);
    }

    //草稿箱删除
    public function delete(Request $request, QzProductModel $QzProductModel)
    {
        $id = $request->post('id');
        $data = $QzProductModel->where('id', $id)->delete();
        return response()->json($data)->setStatusCode(200);
    }

    //权证产品草稿箱发布
    public function news(Request $request, QzProductModel $QzProductModel)
    {
        $id = $request->post('id');
        $data = $QzProductModel->where('id', $id)->update(['withdraw' => 2]);
        if ($data) {
            return response()->json(['code' => 200]);
        } else {
            return response()->json(['code' => 433]);
        }
    }
}