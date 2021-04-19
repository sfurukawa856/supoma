<?php
session_start();
require '../../common/auth.php';
require_once './myUtil.php';
require_once('../../common/database.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$id = $_SESSION['user']['id'];

// DB接続
try {
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT name,email FROM user WHERE id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', $id, PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

if (!empty($_POST)) {
    if (!cken($_POST)) {
        $encoding = mb_internal_encoding();
        $err = "文字エンコードでエラーが発生しました。" . $encoding;
        exit($err);
    } else {
        $to = "tennis856@gmail.com,losenshu.ittve0744@gmail.com,tkuckzk11391224@gmail.com";
        $title = "スポマお問い合わせ";
        $contact = es($_POST['contact']);
        $from = $result[0]['name'];
        $email = $result[0]['email'];
    }
    mb_language("japanese");
    mb_internal_encoding("UTF-8");

    if (mb_send_mail("$to", "$title", "$contact", "FROM:" . mb_encode_mimeheader("$from") . "<$email>")) {
    } else {
    }
} else {
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../../common/head.php");
    echo getHeader("お問い合わせ完了");
    ?>
    <link rel="stylesheet" href="../../public/css/thanks.css">
</head>

<body>


    <div>
        <h1>お問い合わせ 送信完了</h1>
        <p>
            スポマにご連絡いただきありがとうございます。<br>
            お問い合わせ内容を受け付けました。<br>
            内容を確認のうえ、回答させていただきます。<br>
            しばらくお待ちください。
        </p>
        <a href="../table.php">
            <button type="button">トップページへ</button>
        </a>
    </div>
</body>

</html>