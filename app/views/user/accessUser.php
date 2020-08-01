<form method="post" ">
<?php if (isset($list)) : ?>
    <?php if (empty($list)): ?>
        <h1> No Results Found!!!</h1>
    <?php endif; ?>

    <table>
        <tr>
            <th style="text-align: left">Id</th>
            <th style="text-align: left">Name</th>
            <th style="text-align: left">Email</th>
            <th style="text-align: left">Role</th>
            <th style="text-align: left">Select</th>
        </tr>
        <?php foreach ($list as $user) : ?>
            <tr>
                <td><?php echo $user['id'] ?></td>
                <td><?php echo $user['name'] ?></td>
                <td><?php echo $user['email'] ?></td>
                <td><?php echo $user['role'] ?></td>
                <td><input type="radio" name= "userSelected" value=<?php echo $user['id'] ?>></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<input type="submit" value="selectUser" name="selectUser">
</form>
