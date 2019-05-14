<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登陆</title>
</head>
<body>
<form>
    <font>邮箱：</font>
    <input type="email" id="email"><br>
    <font>密码：</font>
    <input type="password" id="pwd"><br>
    <button id="btn">登陆</button>
</form>
</body>
<script src="js/jquery-3.3.1.min.js"></script>
</html>
<script>
    $(function(){
        $('#btn').click(function(){
            var email=$('#email').val();
            var pwd=$('#pwd').val();
            $.ajax({
                url:'http://vm.api.cn/login',
                type:"post",
                data:{email:email,pwd:pwd},
                dataType:"jsonp",
                jsonpCallback:"person",
                success:function(msg){
                    alert(msg.msg)
//                    window.location.href="";
                }
            });
            return false;
        })
    })
</script>