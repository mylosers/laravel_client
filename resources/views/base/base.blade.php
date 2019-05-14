<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>加密发送测试</title>
</head>
<body>
<form>
    <button id="btn">发送当前时间戳</button>
</form>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    $(function(){
        $('#btn').click(function(){
            $.ajax({
                url     :   '/base',
                type    :   'post',
//            data    :   {goods_id:goods_id,num:num},
                dataType:   'json',
                success :   function(d){
                    console.log(d);
                }
            });
            return false;
        })
    })
</script>
</body>
</html>