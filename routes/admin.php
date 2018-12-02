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
// Route::get('/home', function () {
//     return view('home');
// });
Route::get('/login' , 'Admin\LoginController@login');
Route::post('/dologin', 'Admin\LoginController@DoLogin');

// Route::get('/home', 'Admin/HomeController@index');