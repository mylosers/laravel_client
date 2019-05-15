<?php

namespace App\Http\Controllers\Login;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

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

    }

    /**
     * 登陆
     */
    public function loginInfo(){
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $email=$data['email'];
        $pwd=$data['pwd'];
        if ($email == "") {
            $res = [
                'error' => 50004,
                'msg' => '用户名必填'
            ];
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        } else if ($pwd == "") {
            $res = [
                'error' => 50003,
                'msg' => '密码必填'
            ];
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        $info_table=UserModel::where(['email'=>$email])->first();
        if($info_table){
            //TODO 登陆逻辑
            //判断密码是否正确
            $info = password_verify($pwd, $info_table->pwd);
            if ($info == true) {
                $token = $this->token($info_table->id);//生成token
                /*$key='login_token';
                Redis::setex($key,3600,$token.','.$uid->id);
                $val=Redis::get($key); //查询key值中的val值
                $arr=explode(',',$val); //根据，切割字符串为数组 explode*/
                $redis_token_key = 'login_token:id:' . $info_table->id;
                Redis::set($redis_token_key, $token);
                Redis::expire($redis_token_key, 604800);
                /*setcookie('token',Str::random(6),time()+50,'/','client.myloser.club',false,true);
                setcookie('id',999,time()+50,'/','client.myloser.club',false,true);*/
                $res = [
                    'error' => 0,
                    'msg' => '登陆成功',
                    'data' => [
                        'token' => $token,
                        'id'=>$info_table->id
                    ]
                ];
                die(json_encode($res, JSON_UNESCAPED_UNICODE));
            } else {
                //密码不正确
                $res = [
                    'error' => 50005,
                    'msg' => '密码不正确'
                ];
                die(json_encode($res, JSON_UNESCAPED_UNICODE));
            }
        }else{
            //查无此人
            $res = [
                'error' => 50004,
                'msg' => '没有此用户'
            ];
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * token
     */
    public function token($id)
    {
        return substr(sha1($id . time() . Str::random(10)), 5, 15);
    }
}