<?php
namespace App\Http\Controllers\Admin;

use App\Model\QzAdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class LoginController extends Controller
{
    public function Login(ResponseFactoryContract $response){
        return $response->json(['message' => '用户名或密码不正确','code' => '40024']);
    }
    public function DoLogin(Request $request,ResponseFactoryContract $response){
        $username = $request->username;
        $this->validate(
            $request,['username' => ['required']],['username.required'=>"用户名不能为空"]
        );
        $this->validate(
            $request,['password' => ['required']],['password.required'=>"密码不能为空"]
        );
        $password =  md5(md5($request->password).env('PLATFORM_KEY'));  
        $userdetial = QzAdminUser::where('name' , $username)->where('password' , $password)->first();
        return  $userdetial;
    }
}
