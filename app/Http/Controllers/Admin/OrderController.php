<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 12:23
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\QzOrder;
use App\Model\User;

class OrderController extends Controller
{
    /**
     * 订单列表
     */
    public function lists(Request $request)
    {
        $product_name = $request->post('product_name');
        $product_num = $request->post('product_num');
        $builder = with(new QzOrder())->setHidden([])->newQuery();
        foreach (
            [
                     'product_name' => [
                         'operator' => 'like',
                         'value' => sprintf('%%%s%%', $product_name),
                         'condition' => boolval($product_name),
                     ],
                     'id' => [
                         'operator' => 'like',
                         'value' => sprintf('%%%s%%', $product_num),
                         'condition' => boolval($product_num),
                     ],

            ] as $key => $item) {
            if ($item['condition']) {
                $builder->where($key, $item['operator'], $item['value']);
            }
        }
            $data = $builder->orderBy('create_time', 'desc')->paginate(15);
            return $data;
            $data->each(function ($item) {
                $item['user_name'] = User::where('id', $item['user_id'])->value('name');
            });
            return response()->json($data)->setStatusCode(200);
    }

    /**
     * 导出下载.
     *
     * @param Request $request
     */
    public function export_order(Request $request)
    {
        if ($request->get('export_type') === 'statistic') {
            $title = ['打赏次数', '打赏金额（元）', '打赏日期'];
            $data = $this->byConditionsGetStatisticsData($request);
            $this->exportExcel($data, $title, '打赏统计');
        } else {
            $title = ['打赏用户', '被打赏用户', '金额（元）', '打赏应用', '打赏时间'];
            $items = $this->byConditionsGetRewardData($request);
            $data = $this->convertRewardData($items);
            $this->exportExcel($data, $title, '打赏详情');
        }
    }

    /**
     * export excel.
     *
     * @param array $data  数据
     * @param array $title 列名
     * @param string $filename
     */
    public function exportExcel(array $data = [], array $title = [], $filename = 'export')
    {
        //set response header
        header('Content-type:application/octet-stream');
        header('Accept-Ranges:bytes');
        header('Content-type:application/vnd.ms-excel');
        header(sprintf('Content-Disposition:attachment;filename=%s.xls', $filename));
        header('Pragma: no-cache');
        header('Expires: 0');

        if (count($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv('UTF-8', 'GB2312', $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }

        if (count($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv('UTF-8', 'GB2312', $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
            }
            echo implode("\n", $data);
        }
    }


}