<?php
session_start();

require '../common/auth.php';
require_once('../memo/action/myUtil.php');
// クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

if (isset($_SESSION['collect']['check'])) {
    unset($_SESSION['collect']);
    header('Location: ../memo/');
    exit;
};

$id = $_SESSION['user']['id'];

$title = $_SESSION['userpost']['title'];
$category = $_SESSION['userpost']['category'];
$member = $_SESSION['userpost']['member'];
$eventDate = $_SESSION['userpost']['eventDate'];
$eventEndDate = $_SESSION['userpost']['eventEndDate'];

$replace = str_replace("T", " ", $eventDate);
$replace2 = str_replace("-", "/", $replace);

$replace3 = str_replace("T", " ", $eventEndDate);
$replace4 = str_replace("-", "/", $replace3);

$place = $_SESSION['userpost']['place'];
$start_time = $_SESSION['userpost']['start_time'];
$startTime  = str_replace("-", "/", mb_substr($start_time, 5));

$end_time = $_SESSION['userpost']['end_time'];
$endTime  = str_replace("-", "/", mb_substr($end_time, 5));

$message = $_SESSION['userpost']['message'];
$filename = $_SESSION['userpost']['filename'];
$tmp_path = $_SESSION['userpost']['tmp_path'];
$save_path = $_SESSION['userpost']['save_path'];
$save_filename = $_SESSION['userpost']['save_filename'];


?>

<?php

//userinfoのデータ取得
try {
    require_once('../common/database.php');
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT * FROM user,userinfor WHERE id=:id AND user_id=id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}


//通知数
try {
    $sql = "SELECT SUM(count) FROM news WHERE news_id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../common/head.php");
    echo getHeader("募集個別確認ページ");
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/individual.css">
</head>

<body>


    <?php
    require_once('../common/header.php');
    ?>

    <main class="main">


        <?php

        $url2 = "http://localhost/GroupWork/20210329_spoma-main/images/{$save_filename}";

        ?>

        <div class="main-sp-img">
            <img src="<?php echo $url2; ?>" alt="">
        </div>
        <div class="main-wrap">

            <span class="main-category"><?php echo es($category); ?></span>
            <h1 class="main-title"><?php echo es($title); ?></h1>
            <div class="main-top-img">
                <img src="<?php echo $url2; ?>" alt="">
            </div>
            <div class="flex">
                <div class="flex-l">
                    <dl class="main-items">
                        <div class="main-items-wrap">
                            <dt class="item">開催日時</dt>
                            <dd class="answer">
                                <time datetime="<?php echo mb_substr($replace2, 5, 11); ?>">
                                    <?php echo mb_substr($replace2, 5, 11); ?> ~ </time>
                                <time datetime="<?php echo mb_substr($replace4, 5, 11); ?>">
                                    <?php echo mb_substr($replace4, 5, 11); ?></time>
                            </dd>
                        </div>
                        <div class="main-items-wrap">
                            <dt class="item">募集期間</dt>
                            <dd class="answer">
                                <?php
                                echo $startTime; ?>~<?php echo $endTime; ?>
                            </dd>
                        </div>
                        <div class="main-items-wrap">
                            <dt class="item">募集人数</dt>
                            <dd class="answer"><?php echo $member; ?>人</dd>
                        </div>
                        <div class="main-items-wrap">
                            <dt class="item">開催場所</dt>
                            <dd class="answer"><?php echo es($place); ?></dd>
                        </div>
                        <dt class="message">メッセージ</dt>
                        <dd class="text"><?php echo $message; ?></dd>
                    </dl>

                    <form action="#" method="POST" class="form">
                        <h2 class="comment">コメント欄<span class="notification">※応募者が質問、コメントする欄になります</span></h2>
                        <p class="info">相手のことを考え丁寧なコメントを心がけましょう</p>
                        <textarea name="message" class="message" placeholder="コメントする..."></textarea>
                        <div class="form-btn">
                            <button type="submit">コメントする</button>
                        </div>
                    </form>
                </div>


                <div class="main-btn-wrap">
                    <div class="main-btn-wrap-flex">
                        <a href="./action/collect.php" class="return" class="main-btn">確定</a>
                        <a href="./action/imgRemove.php" class="return">修正する</a>
                        <div class="side-block">
                            <h2>開催者</h2>
                            <?php
                            $nickname = $dbResult[0]['nickname'];
                            ?>
                            <p><?php echo es($nickname); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-transparent">
        </div>
    </main>
    <hr>

    <?php
    require '../common/footer.php';
    ?>

    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js"></script>
    <script src="../public/js/contact.js" type="module"></script>
</body>

</html>