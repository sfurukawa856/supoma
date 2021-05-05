<?php
session_start();
require_once('../common/auth.php');
require_once('./action/myUtil.php');
require_once('../common/database.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$id = $_SESSION['user']['id'];

// DB接続
$dbConnect = getDatabaseConnection();

// 退会ユーザーの年齢、性別、好きなスポーツを検索
try {
    $sql = "SELECT sports,sex,age FROM userinfor WHERE user_id=:user_id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':user_id', $id, PDO::PARAM_INT);
    $stm->execute();
    $selectResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "1.データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit;
}

$reasons = $_SESSION['cancel']['reason'];

// 退会理由用文字列
$cancelReasons = [];
foreach ($reasons as $value) {
    array_push($cancelReasons, $value);
}
if (!empty($_SESSION['cancel']['other'])) {
    $cancelReasons[] = $_SESSION['cancel']['other'];
}

$separate_cancelReasons = implode("\n", $cancelReasons);


// 退会ユーザーの年齢、性別、好きなスポーツと退会理由をDBに保存
try {
    $sql = "INSERT INTO cancel(sports,sex,age,reason) VALUES (:sports,:sex,:age,:reason)";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':sports', $selectResult[0]['sports']);
    $stm->bindValue(':sex', $selectResult[0]['sex']);
    $stm->bindValue(':age', $selectResult[0]['age']);
    $stm->bindValue(':reason', $separate_cancelReasons);
    $stm->execute();
} catch (Exception $e) {
    echo "2.データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

// メール送信
try {
    $sql = "SELECT name,email FROM user WHERE id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', $id, PDO::PARAM_INT);
    $stm->execute();
    $mailResult = $stm->fetchAll(PDO::FETCH_ASSOC);
    $cancelName = $mailResult[0]['name'];

    // $to = "tennis856@gmail.com,losenshu.ittve0744@gmail.com,tkuckzk11391224@gmail.com";
    $to = $mailResult[0]['email'];
    $title = "スポマをご利用いただきありがとうございました。";
    $content = <<<"EOD"
$cancelName 様

お世話になっております。
スポマ　ユーザー情報管理担当です。

ご依頼頂きましたアカウントの退会処理を完了致しました。

またのご利用をお待ちしております。
今後とも、よろしくお願い申し上げます。
EOD;
    $headers = "From: supoma@xs126549.xsrv.jp";
    $headers .= "\n";
    $headers .= "Bcc: tennis856@gmail.com";

    mb_language("japanese");
    mb_internal_encoding("UTF-8");

    $mailResult = mb_send_mail($to, $title, $content, $headers);

    if (!$mailResult) {
        array_push($_SESSION['errors'], "退会処理に失敗しました。<br>お手数ですが、もう一度退会処理のお手続きをお願いいたします。");
        header('Location:./cancel.php');
        exit;
    }
} catch (Exception $e) {
    echo "4.データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

// ユーザー情報の更新、削除
try {
    $sql = "DELETE FROM news WHERE news_id=:news_id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':news_id', $id, PDO::PARAM_INT);
    $newsResult = $stm->execute();
    if ($newsResult) {
        // 募集期間を終了済みにする
        $sql = "UPDATE userpost SET end_time=(`end_time`-INTERVAL 12 MONTH) WHERE userpost_id=:userpost_id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':userpost_id', $id, PDO::PARAM_INT);
        $stm->execute();

        // userinfo更新
        $sql = "UPDATE userinfor SET nickname='退会済みユーザー',sex=Null,age=Null,file_name='Slogo.png',file_path='http://localhost/GroupWork/20210329_spoma-main/public/images/Slogo.png' WHERE user_id=:user_id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stm->execute();

        // user更新
        $sql = "UPDATE user SET name='退会済みユーザー',email=Null WHERE id=:id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
    }
} catch (Exception $e) {
    echo "3.データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../common/head.php");
    echo getHeader("スポマ退会ページ");
    ?>
    <link rel="stylesheet" href="../public/sass/cancel_fin.css">
</head>

<body>
    <h2 class="cancel">スポマの退会</h2>
    <hr>

    <main>
        <h3 class="title">退会完了</h3>
        <div class="container">
            <p>退会手続き完了のメールをお送りしましたのでご確認ください。<br>ご利用ありがとうございました。</p>
            <p>またのご利用をお待ちしております。</p>
        </div>
        <div class="cancel_end">
            <a href="../index.php">スポマトップページに戻る</a>
        </div>
    </main>
</body>

<?php
killSession();
?>

</html>