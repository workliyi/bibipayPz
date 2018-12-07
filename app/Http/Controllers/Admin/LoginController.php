<?php
namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(){
        return view('index');
    }
    public function DoLogin(Request $request , User $user){
        $username = $request->username;
        $password = $request->password;
        $user = $user->where('id' , 1)->get(); 
        return $user;
    }
}
