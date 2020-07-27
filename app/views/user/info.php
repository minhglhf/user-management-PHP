<form method="post">

    <input type="text" placeholder="permission_access" name="permission_access">
    <input type="text" placeholder="email" name="email">
    <input type="text" placeholder="password" name="password">
    <input type="text" placeholder="address" name="address">
    <input type="text" placeholder="sex" name="sex">
    <input type="text" placeholder="name" name="name">
    <input type="text" placeholder="birth" name="birth">
    <?php if ($this->getCurrentActionName() == "create") : ?>
        <input type="submit" value="insert" name="oke">
    <?php endif; ?>
    <?php if ($this->getCurrentActionName() == "search" || $this->getCurrentActionName() == "update" || $this->getCurrentActionName() == "delete") : ?>
        <input type="submit" value="search" name="oke">
    <?php endif; ?>

</form>
