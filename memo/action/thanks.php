<?php
mb_language("japanese");
mb_internal_encoding("UTF-8");

$to = "tkuckzk11391224@gmail.com";
$title = "スポマお問い合わせ";
$message = $_POST['contact'];

mb_send_mail(
    $to,
    $title,
    $message
)
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../../common/header.php");
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
        <a href="../news.php">
            <button type="button">トップページへ</button>
        </a>
    </div>
</body>

</html>