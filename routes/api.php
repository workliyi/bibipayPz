<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Routing\Registrar as RouteContract;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'ucenter'],function(RouteContract $api){
    //用户相关操作接口
    $api->group(['prefix' => 'v2'],function (RouteContract $api){
        //权证首页显示接口
        $api->get('/qzhome' , 'APIS\QzHomeController@index');
        //权证产品详细信息
        $api->get('/pdetail' , 'APIS\QzHomeController@productDetail');
        //权证用户订单详细信息
        $api->get('/odetail' , 'APIS\QzHomeController@orderDetail');
        //购买权证产品接口
        $api->post('/buygood' , 'APIS\QzBuyController@buyGoods');
        //权证产品列表接口
        $api->get('/olist' , 'APIS\QzDetailsController@index');
        //取消订单
        $api->post('/delorder' , 'APIS\QzDetailsController@delOrder');
        //获取用户行权相关数据信息
        $api->post('/exerdetial' , 'APIS\QzDetailsController@exerciseDetial');
        //行权详情
        $api->post('/detexecute' , 'APIS\QzDetailsController@exe_detail');
        //获取用户邀请码
        $api->get('/getcode' , 'APIS\QzDetailsController@getCode');
        //用户行权
        $api->post('/execute' , 'APIS\QzDetailsController@execute');
    });
});


