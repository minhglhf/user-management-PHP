<form method="post" ">
<?php if (isset($list)): ?>
    <h2>Current Record</h2>
    <p style="display: block">id = <input type="text" name="id" value=<?php echo $list['id']; ?> readonly></p>
    <p style="display: block">role = <input type="text" name="role" placeholder="<?php echo $list['role']; ?>"></p>
    <span style="color: red"><?php if (isset($listCreate['role_create'])) echo $listCreate['role_create']; ?></span>
    <p style="display: block">email = <input type="text" name="email" placeholder="<?php echo $list['email']; ?>"></p>
    <span style="color: red"><?php if (isset($listCreate['email_create'])) echo $listCreate['email_create']; ?></span>
    <p style="display: block">password = <input type="text" name="password"></p>
    <span style="color: red"><?php if (isset($listCreate['password_create'])) echo $listCreate['password_create']; ?></span>
    <p style="display: block">address = <input type="text" name="address" placeholder="<?php echo $list['address']; ?>"></p>
    <p style="display: block">sex = <input type="text" name="sex" placeholder="<?php echo $list['sex']; ?>"></p>
    <span style="color: red"><?php if (isset($listCreate['sex_create'])) echo $listCreate['sex_create']; ?></span>
    <p style="display: block">name = <input type="text" name="name" placeholder="<?php echo $list['name']; ?>"></p>
    <span style="color: red"><?php if (isset($listCreate['name_create'])) echo $listCreate['name_create']; ?></span>
    <p style="display: block">birth = <input type="text" name="birth" placeholder="<?php echo $list['birth']; ?>"></p>
    <span style="color: red"><?php if (isset($listCreate['birth_create'])) echo $listCreate['birth_create']; ?></span>
    <input type="submit" value="updateData" name="updateData" id="updateData">
<?php endif; ?>
</form>
