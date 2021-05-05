<?php
session_start();
require_once('../../memo/action/myUtil.php');
require '../../common/auth.php';

if (!isLogin()) {
    header('Location:../../index.php');
    exit;
}

// エンコードチェック
if (!cken($_POST)) {
    $encoding = mb_internal_encoding();
    $err = "エンコードエラーです。予期せぬエンコードは" . $encoding;
    exit($err);
}
$_POST = es($_POST);
?>

<?php
if (empty($_POST['other'])) {
    unset($_POST['other']);
}

// 退会理由選択有無のチェック
$_SESSION['errors'] = [];

if (empty($_POST)) {
    array_push($_SESSION['errors'], "退会理由を一つ以上選択してください。");
    header('Location:../cancel.php');
    exit;
} else {
    $_SESSION['cancel'] = $_POST;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../../common/head.php");
    echo getHeader("スポマ退会ページ");
    ?>
    <link rel="stylesheet" href="../../public/sass/cancel_check.css">
</head>

<body>
    <h2 class="cancel">スポマの退会</h2>
    <hr>
    <main>
        <h3 class="title">退会理由</h3>
        <div class="container">
            <?php
            if (!empty($_SESSION['cancel']['reason'])) {
                foreach ($_SESSION['cancel']['reason'] as $reason) {
                    echo "<p>・{$reason}</p>";
                };
            }
            if (!empty($_SESSION['cancel']['other'])) {
                echo "<p>・{$_SESSION['cancel']['other']}</p>";
            }
            ?>
        </div>
        <div class="final_confirm">
            <p>こちらの内容で問題ないですか。</p>
            <form>
                <input type="button" value="戻る" onclick="location.href='../cancel.php'">
                <input type="button" value="送信する" onclick="location.href='../cancel_fin.php'">
            </form>
        </div>
    </main>

</body>

</html>