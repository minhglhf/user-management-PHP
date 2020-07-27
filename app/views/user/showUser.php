<div style="border: solid 2px green">

    <?php if (isset($list)): ?>
        <?php if (empty($list)): ?>
            <h1> No Results Found!!!</h1>
        <?php endif; ?>
        <?php foreach ($list as $user) : ?>
            <div>
                <span><?php echo $user['name'] . "  " . $user['email'] . "   " . $user['address'] ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<a href=" ../user/index"><input type="submit" name="backToHomePage" value="back to home pager"></a>