<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
class BaseController extends Controller
{
    /**
     * 对称加密方法
     */
    public function encode($str,$key){				//加密
        $strArr = str_split(base64_encode($str));
        $strCount = count($strArr);
        foreach(str_split($key) as $k => $v) {
            $strArr[$k]=$strArr[$k].$v;
        }
        $newStr = join('',$strArr);

        return $newStr;
    }

    /**
     * 对称解密方法
     */
    function decode($str,$key){				//解密
        $strArr = str_split($str,2);
        foreach (str_split($key) as $k => $v) {
            if($strArr[$k][1] === $v){
                $strArr[$k]=$strArr[$k][0];
            }
        }
        $newInfo = join("",$strArr);
        $newInfo = base64_decode($newInfo);

        return $newInfo;
    }

    /**
     * 测试加密
     */
    public function base(){
        $str=time();
        $miStr = $this->encode($str,$key="wenzi");	//加密
        echo $miStr;
        $url = "http://vm.api.cn/foo";
        $data = json_encode(["foo" => "$miStr"]);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_exec($curl);
        $error=curl_errno($curl);
        if($error>0){
            die('失败');
        }
        curl_close($curl);
    }

    /**
     * 页面
     */
    public function baseList(){
        return view('base.base');
    }

    /**
     * 测试解密
     */
    public function baseNO(){
        $miStr="MwTeUn1zNizQwMzIwOA==";  //测试死值
        $info =$this-> decode($miStr,$key = "wenzi");	//解密
        echo $info;
    }

    /**
     * 测试解密-2
     */
    public function off(){
        $b64=$_GET['b64'];
        $str=base64_decode($b64);
        $method='AES-256-CBC';
        $key='xxyyzz';
        $option=OPENSSL_RAW_DATA;
        $vi='czy010118lyq1314';
        $enc_str=openssl_decrypt($str,$method,$key,$option,$vi);
        echo 'b64：'.$b64;echo "<br>";
        echo '原文：'.$enc_str;echo "<br>";
        echo '密文：'.$str;echo "<br>";
    }

    /**
     * 非对称加密测试
     */
    public function rsa(){
        $data=[
            'name'=>'loser',
            'pwd'=>'czy010118',
            'email'=>'3092256637@qq.com'
        ];
        //加密
        $json_str=json_encode($data);
        $k=openssl_pkey_get_private('file://'.storage_path('app/keys/private.pem'));
        //加密
        openssl_private_encrypt($json_str,$enc_data,$k);
        $b64=base64_encode($enc_data);
        echo $enc_data;echo "<hr>";
        echo $b64;
        $url = "http://vm.api.cn/rsaNo";
        $data = json_encode(['foo'=>$b64]);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_exec($curl);
//        $error=curl_errno($curl);
        curl_close($curl);
    }

    /**
     * 非对称加密测试-2
     */
    public function sign(){
        $data=[
            'name'=>'loser',
            'pwd'=>'czy010118',
            'email'=>'3092256637@qq.com'
        ];
        $json_str=json_encode($data);
        //计算签名 使用私钥对数据签名
        $k=openssl_pkey_get_private('file://'.storage_path('app/keys/private.pem'));
        openssl_sign($json_str,$signature,$k);
//        echo "signature:$signature";
        $b64=base64_encode($signature);
        echo 'signature:'.$signature;echo "<hr>";
        echo 'b64:'.$b64;echo "<hr>";

        $url = 'http://vm.api.cn/sign?sign='.urlencode($b64);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$json_str);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Conten-Type:text/plain']);
        curl_exec($curl);
        $error=curl_errno($curl);
        if($error>0){
            echo "CURL 错误码：".$error;exit;
        }
        curl_close($curl);
    }
}
