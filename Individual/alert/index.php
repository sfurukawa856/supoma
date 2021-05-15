<?php
session_start();
require_once('../../common/database.php');
require_once('../../common/auth.php');

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}

$id = $_SESSION['user']['id'];

$dbConnect = getDatabaseConnection();
