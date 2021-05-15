<?php
// クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    //共通ファイル読み込み
    require_once('./common/head.php');
    //head取得
    echo getHeader("スポマトップページ");
    ?>
    <!--==============レイアウトを制御する独自のCSSを読み込み===============-->
    <link href="https://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/reset.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/loading.css">
    <link rel="stylesheet" href="./public/css/top.css">
</head>

<body>
    <div id="splash">
        <div id="splash_logo">
            <img src="public/images/supomalogo2.png" alt="" class="fadeUp">
        </div>
    </div>

    <main>
        <div id="container">
            <div class="left">
                <div class="head">
                    <div class="logo">
                        <img src="public/images/Slogo.png" alt="ロゴ">
                    </div>
                    <h2 class="name">スポマ</h2>
                </div>
                <div class="text">
                    <p><span>ス</span>ポーツマンによる</p>
                    <p>ス<span>ポ</span>ーツマンのための</p>
                    <p><span class="white">マ</span>ッチングアプリ</p>
                </div>
                <div class="btn">
                    <p><input type="button" value="新規登録" onclick="location.href='./user/'"></p>
                    <p><input type="button" value="ログイン" onclick="location.href='./login/'"></p>
                </div>
            </div>
            <div class="right">
                <img src="public/images/sports.png" alt="スポーツロゴ">
            </div>
            <!--/container-->
        </div>
    </main>
    <!--==============JQuery読み込み===============-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="./public/js/index.js"></script>
</body>

</html>