<form method="post" ">
<?php if (isset($list)): ?>
    <h2>Current Record</h2>
    <?php foreach ($list as $user) : ?>
        <p style="display: block">id = <input type="text" name="value0" value=<?php echo $user['id'] ?> readonly
                                                          ></p>
        <p style="display: block">permission_access = <input type="text" name="value1"
                                                             placeholder= <?php echo $user['permission_access'] ?>></p>
        <p style="display: block">email = <input type="text" name="value2"
                                                 placeholder= <?php echo $user['email'] ?>></p>
        <p style="display: block">password = <input type="text" name="value3"
                                                    placeholder= <?php echo $user['password'] ?>></p>
        <p style="display: block">address = <input type="text" name="value4"
                                                   placeholder= <?php echo $user['address'] ?>></p>
        <p style="display: block">sex = <input type="text" name="value5"
                                               placeholder= <?php echo $user['sex'] ?>></p>
        <p style="display: block">name = <input type="text" name="value6"
                                                placeholder= <?php echo $user['name'] ?>></p>
        <p style="display: block">birth = <input type="text" name="value7"
                                                 placeholder= <?php echo $user['birth'] ?>></p>
        <input type="submit" value="updateData" name="updateData" id="updateData">
    <?php endforeach; ?>

<?php endif; ?>

</form>
