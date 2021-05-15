<?php

session_start();
require '../../common/database.php';
require '../../common/auth.php';


if (!isLogin()) {
    header('Location:../../login/');
    exit;
}

$id = $_SESSION['user']['id'];

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    //共通ファイル読み込み
    require_once('../../common/head.php');
    //head取得
    echo getHeader("レビュー画面");
    ?>
    <link rel="icon" href="../../public/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../../public/images/apple-touch-icon.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../public/css/rest.css" />
    <link rel="stylesheet" href="../../public/sass/review.css">
</head>

<body>
    <?php
    require_once('../../common/header.php');
    ?>
    <main class="main">

        <h1>「テニスする人募集」イベントが終了しました。<br>開催者のレビューをしよう！</h1>
        <ul>
            <li><img src="<?php echo $url ?>" alt="" width="50"></li>
            <li>
                <h2>名前 : sample1 さん</h2>
            </li>
            <li>
                <h2>タイトル :テニスする人募集 </h2>
            </li>
            <li>
                <h2>画像エリア</h2>
            </li>
        </ul>

        <form type="POST" action="../action/review_upload.php">
            <div class="review">
                <input id="star1" type="radio" name="star" value="5" />
                <label for="star1"><span class="text"></span>★</label>
                <input id="star2" type="radio" name="star" value="4" />
                <label for="star2"><span class="text"></span>★</label>
                <input id="star3" type="radio" name="star" value="3" />
                <label for="star3"><span class="text"></span>★</label>
                <input id="star4" type="radio" name="star" value="2" />
                <label for="star4"><span class="text"></span>★</label>
                <input id="star5" type="radio" name="star" value="1" />
                <label for="star5"><span class="text"></span>★</label>
            </div>
            <input type="submit" value="評価して完了する">
        </form>
    </main>
    <?php
    require '../../common/footer.php';
    ?>

    <script src="../../public/js/script.js"></script>
</body>

</html>