<!--<link rel="stylesheet" type="text/css" href="./public/css/login.css">-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Login page</title>
</head>
<body>

</body>

<div class="loginBackground" style="border: 2px solid lightskyblue;
;background-color: aliceblue ; width: 400px; padding: 30px; display: inline-block; position: fixed; top: 50%;left: 50%;transform: translate(-50%, -50%);">
    <h1>~USER MANAGEMENT~</h1>
    <img src="../Public/img/user-management.jpg" alt="logo" width="120px" height="80px">
    <div class="login">
        <form method="post" action="">
            <div class="field">

                <label for="email"><strong>Email: </strong></label><br>
                <input type="text" id="email" name="email" value = "<?php if(isset($list['save_email'])) echo $list['save_email']; ?>">
                <span style="color: red"><?php if (isset($list['email'])) echo $list['email']; ?></span>
            </div>
            <div class="field">

                <label for="password"><strong>Password: </strong></label><br>
                <input type="password" id="password" name="password" >
                <span style="color: red"><?php if (isset($list['password'])) echo $list['password']; ?></span>
            </div>
            <div>
                <input type="submit" name="login" value="login" class="button">
            </div>
            </body>
            </html>

        </form>

        <a href="https://www.facebook.com/v7.0/dialog/oauth?
        response_type=code&
        client_id=711452766301403&
        redirect_uri=https://quanly.com/login/index&
        state=eciphp&
        scope=email&
        auth_type=rerequest">
            <img src="../Public/img/loginFB.png" alt="login facebook" width="200px" height="50px"></a>
    </div>
</div>
</body>

