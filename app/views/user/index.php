<div class="loginBackground" style="border: 2px solid lightskyblue;
;background-color: aliceblue ; width: 550px; height: 300px; padding: 50px; display: inline-block; position: fixed; top: 50%;left: 50%;transform: translate(-50%, -50%);">
    <h1>-- USER MANAGEMENT--</h1>
    <a href=" ../login/logout"><input type="submit" name="logout" value="logout"></a>
    <a href=" ../user/showUser"><input type="submit" name="showUser" value="showUser"></a>
    <a href=" ../user/create"><input type="submit" name="create" value="create"></a>
    <a href=" ../user/search"><input type="submit" name="search" value="search"></a>
    <a href=" ../user/update"><input type="submit" name="update" value="update"></a>
    <a href=" ../user/restore"><input type="submit" name="restore" value="restore"></a>
    <br><br>

    <h3>You are login as :
        <?php if (isset($roles)) echo $roles[$this->getModel()->getCurrentUserId($_SESSION['login']['email'])['role']]; ?>
    </h3>
</div>
