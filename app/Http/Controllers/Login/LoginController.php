<?php

namespace App\Http\Controllers\Login;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    /**
     * 登陆
     */
    public function Login(){
        return view('login.login');
    }
    public function loginAdd(){
        $email=$_POST['email'];
        $pwd=$_POST['pwd'];
        $data=[
            'email'=>$email,
            'pwd'=>$pwd
        ];
        if($email==""||$pwd=""){
            $res=[
                'error'=>50004,
                'msg'=>'数据不能为空'
            ];
            return json_encode($res,JSON_UNESCAPED_UNICODE);
        }
        //加密
        $json_str=json_encode($data);
        $k=openssl_pkey_get_private('file://keys/private.pem');
        //加密
        openssl_private_encrypt($json_str,$enc_data,$k);
        $b64=base64_encode($enc_data);
//        echo $enc_data;echo "<br>";
//        echo $b64;die;
        $url = 'http://api.myloser.club/login';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$b64);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Conten-Type:text/plain']);
        $info=curl_exec($curl);
        $error=curl_errno($curl);
        if($error>0){
            echo "CURL 错误码：".$error;exit;
        }
        curl_close($curl);
    }

    /**
     * 验证是否登陆
     */
    public function loginOn(){
        $key='login_token';
        $i=Redis::get($key);
        var_dump($i);
    }
}