<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 添加商品
    public function addCart(){
        // 商品id 购买数量 购买用户
        $gid = intval(request()->gid);
        $buy_numer = intval(request()->buy_number);
        $uid = Auth::id();

        // 查看商品是否下架
        $goodsInfo = DB::table("shop_goods")->where('goods_id',$gid)->first();
        if(!$goodsInfo){
            echo "参数错误";die;
        }
        if($goodsInfo->is_up == 2){
            echo "很抱歉，该商品暂时下架了，请换一件吧！";die;
        }

        // 查看购物车是否已近添加过该商品
        $cartInfo = DB::table("cart")->where(['gid' => $gid,'uid' => $uid,'status' => 1])->first();
        if($cartInfo){
            $update = [];
            // 查看商品价格是否变动 变动的话修改为变动的价格
            if($cartInfo->price != $goodsInfo->self_price){
                $update['price'] = $goodsInfo->self_price;
            }

            // 叠加购买数量
            $new_buy_number = $cartInfo->buy_number + $buy_numer;
            $update['buy_number'] = $new_buy_number;

            // 数据修改
            $res = DB::table("cart")->where('cid',$cartInfo->cid)->update($update);
            if($res){
                echo "加入购物车成功";
            }else{
                echo "加入购物车失败";
            }
        }else{
            // 数据入库
            $data = [
                'gid'           =>  $gid,
                'uid'           =>  $uid,
                'buy_number'    =>  $buy_numer,
                'price'         =>  $goodsInfo->self_price,
                'add_time'      =>  time()
            ];
            $cid = DB::table("cart")->insertGetId($data);
            if($cid){
                echo "<pre>";print_r($data);echo "</pre>";
                echo $cid;echo "<br />";
                echo "加入购物车成功";
            }
        }
    }

    // 删除购物车
    public function delCart(){
        // 购物车商品id
        $cid = intval(request()->cid);

        // 查看是否删除
        $status = DB::table("cart")->where('cid',$cid)->first('status');
        if($status){
            $res = DB::table("cart")->where('cid',$cid)->update(['status'=>2]);
            if($res){
                echo "删除成功";
            }else{
                echo "删除失败";
            }
        }else{
            echo "无效的参数";die;
        }
    }

    // 修改数量
    public function updCart(){
        // 购买数量修改  参数：购物车商品id  购买数量
        $cid = intval(request()->cid);
        $buy_number = intval(request()->buy_numer);

        // 参数准备
        $cartInfo = DB::table("cart")->where(['cid' => $cid,'status' => 1])->first();
        if($cartInfo){
            $goodsInfo = DB::table("shop_goods")->where(['goods_id' => $cartInfo->gid])->first();
            if($goodsInfo->is_up == 2){
                echo "很抱歉，该商品暂时下架了，请换一件吧！";die;
            }

            // 查看商品价格是否变动 变动的话修改为变动的价格
            $update = [];
            if($cartInfo->price != $goodsInfo->self_price){
                $update['price'] = $goodsInfo->self_price;
            }

            // 购买数量叠加
            $update['buy_number'] = $buy_number + $cartInfo->buy_number;
            $res = DB::table("cart")->where('cid',$cid)->update($update);
            if($res){
                echo '数量修改成功';
            }else{
                echo "数量修改失败";
            }
        }
        echo "暂无该商品信息";die;
    }

    // 数据展示
    public function show(){
        // 购物车数据
        $cartInfo = DB::table("cart as c")
            ->join('users as u','c.uid','=','u.id')
            ->join('shop_goods as g','c.gid','=','g.goods_id')
            ->select('u.name','g.goods_name','g.store','c.buy_number','c.price','c.cid')
            ->where('c.status',1)
            ->get();
//        echo "<pre>";print_r($cartInfo);echo "</pre>";die;
        $data = [
            'cartInfo'          => $cartInfo,
        ];
        return view('cart.show',$data);
    }


}
