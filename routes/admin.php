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
<<<<<<< HEAD
Route::post('/userlist', 'Admin\User@user_list');
=======
Route::post('/userlist', 'Admin\UserController@user_list');
Route::post('/usdtlist', 'Admin\UserController@usdt_recharge');
>>>>>>> a1e9c91ce4c8069a35c66635fa5056f08da527d3
