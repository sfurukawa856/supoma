<?php
session_start();
var_dump($_POST);

var_dump($_SESSION['csrfToken']);
// CSRF対策
if ($_POST['csrf'] !== $_SESSION['csrfToken']) {
    header('Location: ../');
    exit('もう一度入力してください。');
} else {
    unset($_SESSION['csrfToken']);
}

// 登録処理を実装するファイル
session_start();
require '../../common/validation.php';
require '../../common/database.php';

// パラメータ取得
$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];
$user_password = $_POST['user_password'];

// バリデーション
$_SESSION['errors'] = [];

// - 空チェック
emptyCheck($_SESSION['errors'], $user_name, "ユーザー名を入力してください。");
emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを入力してください。");
emptyCheck($_SESSION['errors'], $user_password, "パスワードを入力してください。");

// - 文字数チェック
stringMaxSizeCheck($_SESSION['errors'], $user_name, "ユーザー名は255文字以内で入力してください。");
stringMaxSizeCheck($_SESSION['errors'], $user_email, "メールアドレスは255文字以内で入力してください。");
stringMaxSizeCheck($_SESSION['errors'], $user_password, "パスワードは255文字以内で入力してください。");
stringMinSizeCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上で入力してください。");

if (!$_SESSION['errors']) {
    // - メールアドレスチェック
    mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

    // - ユーザー名・パスワード半角英数チェック
    // halfAlphanumericCheck($_SESSION['errors'], $user_name, "ユーザー名は半角英数字で入力してください。");
    halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数字で入力してください。");

    // - メールアドレス重複チェック
    mailAddressDuplicationCheck($_SESSION['errors'], $user_email, "既に登録されているメールアドレスです。");
}

if ($_SESSION['errors']) {
    header('Location: ../../user/');
    exit;
}

// DB接続処理
try {
    $database_handler = getDatabaseConnection();
    $password = password_hash($user_password, PASSWORD_DEFAULT);

    // セッション変数定義
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;
    $_SESSION['password'] = $password;
} catch (Throwable $e) {
    echo $e->getMessage();
    exit;
}

// ユーザー情報登録画面にリダイレクト
header('Location: ../../user_info/');
exit;
