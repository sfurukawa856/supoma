<?php
session_start();
require '../common/database.php';
require '../common/auth.php';
require_once('../memo/action/myUtil.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}



$message = es($_POST['message']);


// echo $insert_date;
// echo $userpost_id;



//newsテーブルのcountカラム更新

if (!empty($_POST['id_news'])) {
    $idNews = es($_POST['id_news']);
    $dbConnect = getDatabaseConnection();

    try {

        $sql = "UPDATE news SET count = 0 WHERE id = :id";

        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', "$idNews", PDO::PARAM_INT);
        $stm->execute();
    } catch (Exception $e) {
        echo "データベース接続エラーがありました。<br>";
        echo $e->getMessage();
    }
}

$dbConnect = getDatabaseConnection();



$userpost_id = es($_POST['userpost_id']);
$insert_date = es($_POST['insert_date']);


//投稿者のuserpostのデータ取得
try {
    $sql = "SELECT * FROM userpost WHERE userpost_id=:id AND insert_date=:insert_date";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$userpost_id", PDO::PARAM_INT);
    $stm->bindValue(':insert_date', $insert_date, PDO::PARAM_INT);
    $stm->execute();
    $userpostResult = $stm->fetchAll(PDO::FETCH_ASSOC);


    $place = $userpostResult[0]['place'];
    $eventDate = $userpostResult[0]['eventDate'];
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>congraturations</title>
    <link rel="stylesheet" href="../public/css/congra.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">

</head>

<body>
    <div class="logos">
        <div class="logos-item" data-aos="fade-up">
            <img alt="画像1" src="../public/images/1.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="500">
            <img alt="画像2" src="../public/images/2.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="100">
            <img alt="画像3" src="../public/images/3.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="300">
            <img alt="画像4" src="../public/images/4.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="100">
            <img alt="画像5" src="../public/images/5.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="200">
            <img alt="画像6" src="../public/images/6.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="500">
            <img alt="画像7" src="../public/images/7.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="600">
            <img alt="画像8" src="../public/images/8.png">
        </div>
        <div class="logos-item" data-aos="fade-up" data-aos-delay="400">
            <img alt="画像9" src="../public/images/9.png">
        </div>
    </div>
    <div class="main">
        <div class="left">
            <div class="waku" data-aos="fade-up" data-aos-delay="400">
                <p class="red">開催日 : <?php echo mb_substr($eventDate, 5, 11); ?></p>
                <p class="red">開催場所 : <?php echo $place ?></p>

                <?php if (!empty($message)) : ?>
                <p>主催者からのメッセージ</p>
                <p><?php echo $message ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="right">
            <p data-aos="fade-up" data-aos-delay="400">参加が承認されました！</p>
            <div class="congra" data-aos="flip-left" data-aos-delay="600">
                <img src="../public/images/bgyello.png" alt="黄色背景">
                <h1>おめでとうございます！</h1>
            </div>
            <p data-aos="fade-up" data-aos-delay="800">スポマをご利用いただき<br>ありがとうございます。<br>
                楽しんできてくださいね！</p>
            <p data-aos="fade-up" data-aos-delay="900">スポマスタッフ一同</p>
            <a href="./table.php">
                <button type="button" data-aos="fade-up" data-aos-delay="1000">トップページへ</button>
            </a>
        </div>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({
        once: true,
        duration: 600,
    })
    </script>
</body>


</html>