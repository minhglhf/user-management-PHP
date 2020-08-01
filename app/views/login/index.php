<!--<link rel="stylesheet" type="text/css" href="./public/css/login.css">-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Login page</title>
</head>
<body>

</body>
<?php
?>
<div class="loginBackground">
    <div class="login">
        <form method="post" action="">
            <div class="field">

                <label for="email"><strong>Email: </strong></label><br>
                <input type="text" id="email" name="email" placeholder="email">
                <span style="color: red"><?php if (isset($list['email'])) echo $list['email']; ?></span>
            </div>
            <div class="field">

                <label for="password"><strong>Password: </strong></label><br>
                <input type="password" id="password" name="password" placeholder="password">
                <span style="color: red"><?php if (isset($list['password'])) echo $list['password']; ?></span>
            </div>
            <div>
                <input type="submit" name="login" value="login" class="button">
            </div>
            </body>
            </html>

        </form>

        <a href="https://www.facebook.com/v7.0/dialog/oauth?
        response_type=token&
        client_id=711452766301403&
        redirect_uri=https://quanly.com/user/callback">
            <img src="../Public/img/loginFB.png" alt="login facebook" width="200px" height="50px"></a>
    </div>
</div>
</body>

