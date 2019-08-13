<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class alipayController extends Controller
{

    // 支付请求接口
    public function alipay(){
        $url = 'https://openapi.alipaydev.com/gateway.do';
        $order_no = 12321;
        $amount = 100;
//        $arr = DB::table("order")->first();
//        if(){
//
//        }

        // 请求参数
        $requestData = [
            'out_trade_no'          => $order_no,
            'product_code'          => 'FAST_INSTANT_TRADE_PAY',
            "total_amount"          => $amount,
            'subject'               => '订单支付',
        ];

        // 公共请求参数
        $pubData = [
            'app_id'                => 2016092500595340,
            'method'                => 'alipay.trade.page.pay',
            'format'                => 'JSON',
            'return_url'            => env("RETURN"),
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'notify_url'            => env("NOTIFY"),
            'biz_content'           => json_encode($requestData),
        ];

        // 生成签名
        $sign = $this->sign($pubData);
        $pubData['sign'] = $sign;


        // 拼接路径
        $str = '';
        foreach ($pubData as $k=>$v){
            $str .=  $k . "=" . urlencode($v) . '&';
        }
        $newUrl = $url . "?" .rtrim($str,'&');

        // 重定向
        header('Location:'.$newUrl);
    }

    // 生成签名
    public function sign($data){
        // 字典序排序
        ksort($data);

        $str = '';
        // 拼接key-values形式字符串
        foreach($data as $k=>$v){
            if($k != 'sign' || $v != '' || !is_array($v)){
                $str .= $k . "=" .$v . "&";
            }
        }

        // 获取私钥并生成签名
        $private = openssl_get_privatekey("file://".storage_path("app/keys/private.pem"));
        openssl_sign(rtrim($str,'&'),$sign,$private,OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    // 支付宝同步通知
    public function success(){
        echo "<pre>";print_r(json_decode($_GET));echo "</pre>";die;
    }

    // 支付宝异步通知
    public function notify(){
        $data = $_POST;
        is_dir('logs') or mkdir('logs',0777,true);
        file_put_contents('logs/notify.log',date("Y-m-d H:i:s").">>>>>".json_encode($data)."\n",FILE_APPEND);

        // 验签

    }

    // 验签
    public function checksign($data){
        unset($data['sign']);
        unset($data['sign_type']);

        ksort($data);

        $str = '';
        foreach($data as $k=>$v){
            $str .= $k . "=" .$v ."&";
        }

        return rtrim($str,'&');
    }

    // 订单超时未支付，删除订单
    public function deleteOrder(){
        $arr  = DB::table("")->get();
        $time = time();

        foreach ($arr as $v) {
            if($time - $v->add_time > 1800){
                DB::table("")->where('',$arr->id)->delete();
            }
        }
    }
}
