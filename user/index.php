<?php
session_start();
require '../common/auth.php';
// クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

if (isLogin()) {
    header('Location: ../memo/');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    //共通ファイル読み込み
    require_once('../common/head.php');
    //head取得
    echo getHeader("ユーザー登録");

    ?>
    <link rel="stylesheet" href="../public/css/user.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
</head>

<body>

    <div class="userForm">

        <!-- CSRF対策 -->
        <?php
        if (!isset($_SESSION['csrfToken'])) {
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;
        }
        $token = $_SESSION['csrfToken'];
        ?>

        <form action="./action/register.php" method="POST">
            <h1 class="title">新規登録</h1>

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

            <div class="user">
                <label>
                    <input type="text" name="user_name" placeholder="お名前">
                </label>
            </div>
            <div class="email">
                <label>
                    <input type="text" name="user_email" placeholder="youremail@sample.com">
                </label>
            </div>
            <div class="pass">
                <label>
                    <input type="password" name="user_password" id="pass" placeholder="パスワード">
                </label>
            </div>
            <input type="hidden" name="csrf" value="<?php echo $token; ?>">
            <div class="bottom-g">
                <p class="passwordBlok"><input type="checkbox" name="checkbox" id="checkbox" class="checkbox">パスワードを表示する
                </p>
                <button type="submit" class="UserBtn"><i class="fas fa-arrow-right"></i></button>
            </div>
            <div class="login">
                <a href="../login/">ログイン</a>
            </div>
        </form>
    </div>

</body>
<script src="../public/js/password.js"></script>

</html>