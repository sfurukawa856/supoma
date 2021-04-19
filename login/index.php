<?php
session_start();
require '../common/validation.php';
require '../common/database.php';
require '../memo/action/myUtil.php';
require '../common/auth.php';
// クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

//ログインチェック
if (isLogin()) {
    header('Location: ../memo/');
    exit;
}
// アカウントロックの時間をチェック
$dbConnect = getDatabaseConnection();
$sql = "SELECT account_lock FROM user WHERE account_lock IS NOT NULL";
$stm = $dbConnect->prepare($sql);
$stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d H:i:s');

foreach ($result as $value) {
    foreach ($value as $value2) {
        if (floor((strtotime($today) - strtotime($value2)) / 60) >= 30) {
            // echo "30分経った。";
            $sql = "UPDATE user SET account_lock=NULL WHERE account_lock=:account_lock";
            $stm = $dbConnect->prepare($sql);
            $stm->bindValue(':account_lock', $value2, PDO::PARAM_STR);
            $stm->execute();
        } else {
            // var_dump($today) . "<br>";
            // echo "30分未満";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>

    <head>
        <?php
        //共通ファイル読み込み
        require_once('../common/head.php');
        //head取得
        echo getHeader("ログイン");

        ?>
        <link rel="stylesheet" href="../public/css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    </head>
</head>

<body>

    <div class="login">

        <!-- CSRF対策 -->
        <?php
        if (!isset($_SESSION['csrfToken'])) {
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrfToken'] = $csrfToken;
        }
        $token = $_SESSION['csrfToken'];
        ?>

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
            <input type="hidden" name="csrf" value="<?php echo $token; ?>">
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