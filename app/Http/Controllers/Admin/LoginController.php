<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(){
        return view('index');
    }
    public function DoLogin(Request $request){
        $username = $request->all();
        $password = $request->password;
        return $username;
    }
}
