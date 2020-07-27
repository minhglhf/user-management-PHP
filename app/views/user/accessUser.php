<form method="post" ">
<?php if (isset($list)): ?>
    <?php if (empty($list)): ?>
        <h1> No Results Found!!!</h1>
    <?php endif; ?>
    <?php foreach ($list as $user) : ?>
        <label for= <?php echo $user['id'] ?>> <?php echo $user['id'] . "   " . $user['name'] . "    " . $user['email']; ?></label>
        <input type="radio" name= "userSelected" value=<?php echo $user['id'] ?>>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
<input type="submit" value="select" name="select">
</form>
