<?php
session_start();
require '../auth.php';
require_once '../myUtil.php';
require_once('../database.php');

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}

// お問い合わせ者の情報
$id = $_SESSION['user']['id'];
$contactName = $_SESSION['name'];
unset($_SESSION['name']);
$contactEmail = $_SESSION['email'];
unset($_SESSION['email']);

if (!empty($_SESSION['tel'])) {
    $contactTel = $_SESSION['tel'];
    unset($_SESSION['tel']);
} else {
    $contactTel = "記載なし";
}

$contactContent = nl2br($_SESSION['content'], false);
unset($_SESSION['content']);

// お問い合わせ内容送信先
$to = $contactEmail;

// お問い合わせ内容の件名
$subject = "お問い合わせありがとうございます。";

// メール本文
$message = <<<"EOD"
お名前：$contactName 
メールアドレス：$contactEmail
電話番号：$contactTel
お問い合わせ内容：$contactContent

上記内容で承りました。
内容を確認のうえ、回答させていただきます。
しばらくお待ちください。
EOD;

// Bcc先
$headers = "From:supoma@xs126549.xsrv.jp";
$headers .= "\n";
$headers .= "Bcc:tennis856@gmail.com";

mb_language("japanese");
mb_internal_encoding("UTF-8");

$mailResult = mb_send_mail($to, $subject, $message, $headers);

$_SESSION['errors'] = [];

if ($mailResult) {
    // 送信ログ
    $filename = "../../log/" . $id . "_send.log";
    $date = date('Y/m/d H:i:s');
    try {
        $fileObj = new SplFileObject($filename, "ab");
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    // 送信ログ記載内容
    $writeContent = <<< "EOD"
------\r
日時：$date,
お名前：$contactName,
メールアドレス：$contactEmail,
お問い合わせ内容：  $contactContent
------\r
EOD;
    // ファイル書き出し
    $written = $fileObj->fwrite($writeContent);

    // DB接続
    $dbConnect = getDatabaseConnection();
    try {
        $sql = "INSERT INTO log(name,email,tel,date,content) VALUES(:name,:email,:tel,:date,:content)";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':name', $contactName, PDO::PARAM_STR);
        $stm->bindValue(':email', $contactEmail, PDO::PARAM_STR);
        $stm->bindValue(':tel', $contactTel, PDO::PARAM_INT);
        $stm->bindValue(':date', $date, PDO::PARAM_STR);
        $stm->bindValue(':content', $writeContent, PDO::PARAM_STR);
        $stm->execute();
    } catch (Exception $e) {
        echo "接続失敗";
        array_push($_SESSION['errors'], "お問い合わせに失敗しました。<br>お手数ですが、もう一度送信操作をお願いいたします。");
        header('Location:../contact/');
        exit;
    }
} elseif (!$mailResult) {
    array_push($_SESSION['errors'], "メール送信できませんでした。<br>お手数ですが、もう一度送信操作をお願いいたします。");
    header('Location:../contact/');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../head.php");
    echo getHeader("お問い合わせ完了");
    ?>
    <link rel="stylesheet" href="../../public/css/thanks.css">
</head>

<body>
    <?php
    require_once('../header.php');
    ?>


    <main>
        <h2 class="contact">お問い合わせ 送信完了</h2>
        <div class="text">
            <p>
                スポマにご連絡いただきありがとうございます。<br>
                お問い合わせ内容を受け付けました。<br>
                内容を確認のうえ、回答させていただきます。<br>
                しばらくお待ちください。
            </p>
            <a href="../../memo/table/">
                <button type="button">トップページへ</button>
            </a>
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