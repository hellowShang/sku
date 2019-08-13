<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table tbody tr td{
            text-align: left;
        }
    </style>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <title>购物车数据展示</title>
</head>
<body>
    <table border="1" cellpadding="1" cellspacing="0.05">
        <thead>
            <th>编号</th>
            <th>商品</th>
            <th>用户</th>
            <th>单价</th>
            <th>购买数量</th>
            <th>所属店铺</th>
        </thead>
        <tbody>
            @foreach($cartInfo as $k=>$v)
                <tr class='cart' cartid="{{$v->cid}}">
                    <td>{{$k+1}}</td>
                    <td>{{$v->goods_name}}</td>
                    <td>{{$v->name}}</td>
                    <td>{{$v->price}}元</td>
                    <td>{{$v->buy_number}}件</td>
                    <td>{{$v->store}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: right;"><a href="javascript:;" id="clear">结算</a></td>
            </tr>
        </tbody>
    </table>
</body>
<script>
    $(function(){
        // 结算时获取购物车商品id
        $('#clear').click(function(){
            var str = '';
            $(".cart").each(function () {
                str += $(this).attr('cartid') + ',';
            });
            var cid = str.substr(0,str.length-1);

            // ajax传值
            $.get(
                '/order/create',
                {cid:cid},
                function(d){
                    console.log(d);
                },
                'json'
            );
        });
    });
</script>
</html>