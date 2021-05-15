<?php
session_start();
require_once('../database.php');
require_once('../myUtil.php');
require_once('../auth.php');

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}

// エスケープ処理
$_POST = es($_POST);

// エラーメッセージ用配列
$_SESSION['errors'] = [];

// 文字エンコードチェック
if (!cken($_POST)) {
    $encoding = mb_internal_encoding();
    $err = "文字エンコードでエラーが発生しました。" . $encoding;
    array_push($_SESSION['errors'], $err);
    header('Location:../contact/');
    exit($err);
}

// 空チェック
if (empty($_POST['name'])) {
    array_push($_SESSION['errors'], "氏名が入力されていません。");
    header('Location:../contact/');
    exit;
} else {
    $name = $_POST['name'];
    $_SESSION['name'] = $_POST['name'];
}

if (empty($_POST['email'])) {
    array_push($_SESSION['errors'], "メールアドレスが入力されていません。");
    header('Location:../contact/');
    exit;
} else {
    $email = $_POST['email'];
    $_SESSION['email'] = $_POST['email'];
}

if (empty($_POST['tel'])) {
    $tel = "記載なし";
} else {
    $tel = $_POST['tel'];
    $_SESSION['tel'] = $_POST['tel'];
}

if (empty($_POST['content'])) {
    array_push($_SESSION['errors'], "お問い合わせ内容が入力されていません。");
    header('Location:../contact/');
    exit;
} else {
    $content = $_POST['content'];
    $_SESSION['content'] = $_POST['content'];
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../head.php");
    echo getHeader("お問い合わせ内容確認画面");
    ?>
    <link rel="stylesheet" href="../../public/css/contact_check.css">
</head>

<body>
    <?php
    require_once('../header.php');
    ?>


    <main>
        <h2 class="contact">以下の内容で送信してよろしいですか？</h2>
        <!-- <h2>以下の内容で送信してよろしいですか？</h2> -->
        <table class="form-table">
            <tbody>
                <tr>
                    <th>氏名</th>
                    <td>
                        <?php echo $name; ?>
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <?php echo $email; ?>
                    </td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td>
                        <?php echo $tel; ?>
                    </td>
                </tr>
                <tr>
                    <th>お問い合わせ内容</th>
                    <td>
                        <?php echo $content; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="btn_area">
            <a href="../contact/" class="return">戻る</a>
            <a href="../thanks/" class="submit">送信する</a>
        </div>
    </main>
    <hr>
    <?php
    require_once('../footer.php');
    ?>

    <script src="../../public/js/jquery-3.6.0.min.js"></script>
    <script src="../../public/js/script.js"></script>
</body>

</html>