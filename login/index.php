<?php
session_start();

require '../common/auth.php';

//ログインチェック
if (isLogin()) {
    header('Location: ../memo/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>

    <head>
        <?php
        //共通ファイル読み込み
        require_once('../common/header.php');
        //head取得
        echo getHeader("ログイン");

        ?>
        <link rel="stylesheet" href="../public/css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    </head>
</head>

<body>

    <div class="login">
        <form action="./action/login.php" method="POST">
            <h1 class="title">ログイン</h1>
            <?php
            if (isset($_SESSION['errors'])) {
                echo '<div class="alert">';
                foreach ($_SESSION['errors'] as $error) {
                    echo "<p>{$error}</p>";
                }
                echo '</div>';
                unset($_SESSION['errors']);
            }
            ?>
            <div class="email">
                <label>
                    <input type="text" name="user_email" placeholder="メールアドレス">
                </label>
            </div>
            <div class="pass">
                <label>
                    <input type="password" name="user_password" id="pass" placeholder="パスワード">
                </label>
            </div>
            <div class="bottom-g">
                <p class="passwordBlok"><input type="checkbox" name="checkbox" id="checkbox" class="checkbox">パスワードを表示する
                </p>
                <button type="submit" class="loginBtn"><i class="fas fa-arrow-right"></i></button>
            </div>
            <div class="signup">
                <a href="../user/index.php">アカウント作成</a>
            </div>
        </form>
    </div>
</body>
<script src="../public/js/password.js"></script>

</html>
