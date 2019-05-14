<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
class CaesarController extends Controller
{
    /**
     * 加密解密
     */
    public function base($str,$num=3){ //$status=1 时加密 =0时解密
        $pass="";
        $str="myLoser";
        $len=strlen($str);
        for($i=0;$i<$len;$i++){
            ord($str[$i]);
                $p=ord($str[$i])+$num;
                $p=ord($str[$i])-$num;
            $pass.=chr($p);
        }
        echo "原文：$str";echo "<br>";
        echo "密文：$pass";
        echo "<hr>";
    }


    /**
     * 测试加密-2
     */
    public function on(){
        $str="myLoser";
        $method='AES-256-CBC';
        $key='xxyyzz';
        $option=OPENSSL_RAW_DATA;
        $vi='czy010118lyq1314';
        $enc_str=openssl_encrypt($str,$method,$key,$option,$vi);
        $b64=base64_encode($enc_str);
        echo '原文：'.$str;echo "<br>";
        echo '密文：'.$enc_str;echo "<br>";
        echo 'b64：'.$b64;echo "<br>";

        header("refresh:3;url=http://vm.client.cn/off?b64=$b64");die;
    }
}