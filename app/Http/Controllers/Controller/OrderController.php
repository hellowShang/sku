<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 订单生成
    public function create(){
        // 接收cid查询数据
        $cid = explode(',',request()->cid);
        $cartInfo = DB::table("cart as c")
            ->join('users as u','c.uid','=','u.id')
            ->join('shop_goods as g','c.gid','=','g.goods_id')
            ->select('u.name','g.goods_name','g.store','c.buy_number','c.price','c.cid')
            ->where('c.status',1)
            ->whereIn('cid',$cid)
            ->get();

        // 开启事务
        DB::beginTransaction();
        try{
            // 订单号
            $order_no = "1578".substr(str_shuffle(mt_rand(11111,99999).time().rand(111111,999999)),10,10).mt_rand(11111,99999);

            $arr = ['京东','淘宝','拼多多','天猫','唯品会'];
            foreach ($cartInfo as $v){
                foreach($arr as $key=>$val){
                    if($v->store == $val){
                        $num = $key+1;
                        $table = "p_order_".$num;
                        // 订单表数据入库
                        $data = [
                            'uid'               => Auth::id(),
                            'order_no'          => $order_no,
                            'order_amount'      =>'',
                        ];
                        $res = DB::table($table)->insert();
                    }
                }
            }

        }catch (\Exception $e){
            $e->getMessage();
        }


    }


    // 店铺入库
    public function store(){
        $goodsInfo = json_decode(json_encode(DB::table("shop_goods")->select("goods_id")->get()),true);
//        echo "<pre>";print_r($goodsInfo);echo "</pre>";
        foreach ($goodsInfo as $k=> $v){
            foreach ($v as $val){
                $num = $val % 5;
//                echo $num;echo "<br />";
                $arr = ['京东','淘宝','拼多多','天猫','唯品会'];

                DB::table("shop_goods")->where('goods_id',$val)->update(['store' => $num+1]);
            }
        }
    }
}
