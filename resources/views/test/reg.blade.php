<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户注册</title>
</head>
<body>
    <form action="/reg" method="post">
        <input type="text" name="user" placeholder="用户名"><br />
        <input type="password" name="pass1" placeholder="密码"><br />
        <input type="password" name="pass2" placeholder="确认密码"><br />
        <input type="email" name="email" placeholder="邮箱"><br />
        <input type="submit" value="提交">
    </form>
</body>
</html>