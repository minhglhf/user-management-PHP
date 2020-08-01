<form method="post" ">
<?php if (isset($list)): ?>
    <h2>Current Record</h2>
        <p style="display: block">id = <input type="text" name="value0" value=<?php echo $list['id']; ?> readonly></p>
        <p style="display: block">role = <input type="text" name="value1" placeholder= "<?php echo  $list['role']; ?>"></p>
        <p style="display: block">email = <input type="text" name="value2" placeholder= "<?php echo  $list['email'] ;?>"></p>
        <p style="display: block">password = <input type="text" name="value3" placeholder= "<?php echo  $list['password']; ?>"></p>
        <p style="display: block">address = <input type="text" name="value4" placeholder= "<?php echo  $list['address']; ?>"></p>
        <p style="display: block">sex = <input type="text" name="value5" placeholder="<?php echo  $list['sex']; ?>"></p>
        <p style="display: block">name = <input type="text" name="value6" placeholder="<?php echo  $list['name'] ;?>" ></p>
        <p style="display: block">birth = <input type="text" name="value7" placeholder="<?php echo  $list['birth'] ;?>" ></p>
        <input type="submit" value="updateData" name="updateData" id="updateData">
<?php endif; ?>
</form>
