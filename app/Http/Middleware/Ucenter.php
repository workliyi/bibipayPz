<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use App\Model\AuthCodeKey;
class Ucenter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$massage = '')
    {
        $base_token = ($request->basetoken ? $request->basetoken :
            substr($request->header('Authorization'),7, strlen($request->header('Authorization'))));
        $base_token = str_replace(' ','+',$base_token);
        
        $base_arr = explode('.' , $base_token);
       // $plat_key = env('JWT_SECRET');
        $plat_key = config('app.warrant_key');
       //dd($plat_key);
        $user_key = $this->get_user_key($base_arr[0],$plat_key); //解密
        
        if (!empty($user_key)){
            $time = base64_decode($base_arr[1]);
            $get_token = $this->base_token($user_key,$time);
            $last_base_token =  $base_arr[1].'.'.$base_arr[2];
            //dd($last_base_token);
            if ($last_base_token == $get_token){
                $key = ['key'=>$user_key];
                $user = ['user' => User::where('key' , $key)->first()];
                $request->merge($key);//添加参数
                $request->merge($user);
                return $next($request);
            } else {
                abort(403, $massage ?: '你没有权限执行该操作');
            }
        } else {
            abort(403, $massage ?: '你没有权限执行该操作');
        }
    }
    public function base_token($user_key,$time){
        $authcode =new AuthCodeKey();
        //获取平台key
        $key = config('app.warrant_key');
        $time = base64_encode((string)$time);
        // 加密
        $last_user_key = $authcode->authcode($user_key,'ENCODE',$key,0);
        //设置的加密参数
        $data = "$user_key.$time";
        $hmac = hash_hmac("sha256", $data, $key, TRUE);
        //加密后字符串
        $signature = base64_encode($hmac);
        //最终生成的base_token
        $base_token = $time.'.'.$signature;
        return $base_token;
    }
    public function get_user_key($data,$key){
        $authcode = new AuthCodeKey();
        return $authcode->authcode($data,'DECODE',$key,0); //解密
    }
}
