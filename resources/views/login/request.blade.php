<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
</head>
<body>
<form>
    <font>名称：</font>
    <input type="text" id="name"><br>
    <font>邮箱：</font>
    <input type="email" id="email"><br>
    <font>密码：</font>
    <input type="password" id="pwd"><br>
    <button id="btn">注册</button>
</form>
</body>
<script src="js/jquery-3.3.1.min.js"></script>
</html>
<script>
    $(function(){
        $('#btn').click(function(){
            var name=$('#name').val();
            var email=$('#email').val();
            var pwd=$('#pwd').val();
            $.ajax({
                url:'/requestAdd',
                type:"post",
                data:{name:name,email:email,pwd:pwd},
                success:function(msg){
                    alert(msg.msg);
                    window.location.href="/login";
                }
            });
            return false;
        })
    })
</script>