<?php
session_start();

require '../../common/auth.php';

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}

$save_path = $_SESSION['userpost']['save_path'];

unlink($save_path);

// メモ投稿画面にリダイレクト
header('Location:../../memo/apply.php');
exit;