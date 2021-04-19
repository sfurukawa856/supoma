<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';
require '../../memo/action/myUtil.php';

// CSRF対策
if ($_POST['csrf'] !== $_SESSION['csrfToken']) {
    header('Location: ../../user/index.php');
    exit('もう一度入力してください。');
} else {
    unset($_SESSION['csrfToken']);
}

// パラメータ取得
$user_email = $_POST['user_email'];
$user_password = $_POST['user_password'];

// バリデーション
$_SESSION['errors'] = [];

// - 空チェック
emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを入力してください。");
emptyCheck($_SESSION['errors'], $user_password, "パスワードを入力してください。");

// - 文字数チェック
stringMaxSizeCheck($_SESSION['errors'], $user_email, "メールアドレスは255文字以内で入力してください。");
stringMaxSizeCheck($_SESSION['errors'], $user_password, "パスワードは255文字以内で入力してください。");
stringMinSizeCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上で入力してください。");

if (!$_SESSION['errors']) {
    // - メールアドレスチェック
    mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

    // - パスワード半角英数チェック
    halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数字で入力してください。");
}

if ($_SESSION['errors']) {
    header('Location: ../../login/');
    exit;
}

// クッキーの有無を確認
if (isset($_COOKIE['missCounter'])) {
    $misscounter = $_COOKIE['missCounter'];
} else {
    $misscounter = 0;
}

// ログイン処理
$database_handler = getDatabaseConnection();
if ($statement = $database_handler->prepare('SELECT * FROM user WHERE email = :user_email')) {
    $statement->bindParam(':user_email', $user_email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    var_dump($user['account_lock']);
    var_dump(is_null($user['account_lock']));
    if (!$user) {
        $_SESSION['errors'] = [
            'メールアドレスが間違っています。'
        ];
        header('Location: ../../login/');
        exit;
    }

    $name = $user['name'];
    $id = $user['id'];
    //password一致しているかチェック
    if (password_verify($user_password, $user['password']) && is_null($user['account_lock'])) {
        // ユーザー情報保持
        $_SESSION['user'] = [
            'name' => $name,
            'id' => $id
        ];
        setcookie("missCounter", "", time() - 3600);
        header('Location: ../../memo/table.php');
        exit;
    } elseif (!password_verify($user_password, $user['password']) &&  $misscounter <= 4) {
        setcookie("missCounter", ++$misscounter, time() + 60 * 25);
        // アカウントロック機能
        $_SESSION['errors'] = [
            "パスワードが間違っています。<br>5回間違えますとアカウントがロックされます。(" . es($misscounter) . "回目)"
        ];
        header('Location: ../../login/');
        exit;
    } elseif (!password_verify($user_password, $user['password']) &&  $misscounter == 5) {
        $today = date('Y-m-d H:i:s');
        setcookie("missCounter", ++$misscounter, time() + 60 * 25);
        $_SESSION['errors'] = ["アカウントをロックしました。30分後、もう一度お試しください。"];
        $sql = "UPDATE user SET account_lock=:today WHERE email=:email";
        $stm = $database_handler->prepare($sql);
        $stm->bindValue(':email', $user_email, PDO::PARAM_STR);
        $stm->bindValue(':today', $today, PDO::PARAM_STR);
        $stm->execute();
        header('Location: ../../login/');
        exit;
    } else {
        $_SESSION['errors'] = ["ログインできません。"];
        header('Location: ../../login/');
    }
}
