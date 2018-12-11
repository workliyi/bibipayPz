<?php
/**
 * Created by PhpStorm.
 * User: liyi
 * Date: 2018/11/29
 * Time: 下午3:46
 */
Route::get('/', function () {
    return view('index');
}); 
Route::post('/dologin', 'Admin\LoginController@DoLogin');
Route::post('/userlist', 'Admin\UserController@user_list');
Route::post('/usdtlist', 'Admin\UserController@usdt_recharge');
Route::post('/countlog', 'Admin\UserController@account_log');
Route::post('/accountlist', 'Admin\UserController@account');
Route::post('/orderlist', 'Admin\OrderController@lists');
Route::post('/pushproduct', 'Admin\QzProductController@create');
Route::post('/draft', 'Admin\QzProductController@draft');
Route::get('/data', 'Admin\QzProductController@data');
Route::post('/newproduct', 'Admin\QzProductController@news');
Route::post('/delproduct', 'Admin\QzProductController@delete');
Route::post('/withdraw', 'Admin\QzProductController@withdraw');
Route::post('/prolist', 'Admin\QzProductController@lists');
Route::post('/poundage', 'Admin\TokenController@poundage');
Route::get('/retutoken', 'Admin\TokenController@return_token');
Route::get('/getusdt', 'Admin\TokenController@getusdt');
//提现申请审核列表
Route::post('/withdrawlist', 'Admin\WithdrawalsApplyController@lists');
//提现审核操作(通过/拒绝)
Route::post('/examine', 'Admin\WithdrawalsApplyController@examine');