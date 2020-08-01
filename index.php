<?php
session_start();
require_once "./Routes/route.php";
$app = new App();
$app->exec();
