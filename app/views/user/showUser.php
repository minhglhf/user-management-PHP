<div style="border: solid 2px green">

    <form method="post">
    <?php if (empty($list)): ?>
        <h1> No Results Found!!!</h1>
    <?php endif; ?>
    <?php if (!empty($list)): ?>
        <table style="width:100%">
            <tr>
                <th style="text-align: left">Id</th>
                <th style="text-align: left">Name</th>
                <th style="text-align: left">Email</th>
                <th style="text-align: left">Password</th>
                <th style="text-align: left">Role</th>
                <th style="text-align: left">address</th>
                <th style="text-align: left">sex</th>
                <th style="text-align: left">birth</th>
            </tr>
            <?php foreach ($list as $user) : ?>
                <tr>
                    <td><?php echo $user['id'] ?></td>
                    <td><?php echo $user['name'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td><?php echo md5($user['password']) ?></td>
                    <td><?php echo $user['role'] ?></td>
                    <td><?php echo $user['address'] ?></td>
                    <td><?php echo $user['sex'] ?></td>
                    <td><?php echo $user['birth'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </form>
</div>
<a href=" ../user/index"><input type="submit" name="backToHomePage" value="back to home pager"></a>