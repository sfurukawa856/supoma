<?php

session_start();
require '../auth.php';
require_once '../myUtil.php';
require_once('../database.php');

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}
$id = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once('../head.php');
    echo getHeader("会社概要");
    ?>
    <link rel="stylesheet" href="../../public/css/company.css">
</head>

<body>
    <?php
    require_once('../header.php');
    ?>

    <main>


        <h2 class="overview">会社概要</h2>

        <table class="kaisha">
            <tr>
                <th>会社名</th>
                <td>株式会社スポマ</td>
            </tr>
            <tr>
                <th>創業者</th>
                <td>シャイン、しゅう、ふる</td>
            </tr>
            <tr>
                <th>資本金</th>
                <td>0円</td>
            </tr>
            <tr>
                <th>従業員数</th>
                <td>3名</td>
            </tr>
            <tr>
                <th>所在地</th>
                <td>あなたの心の中心街</td>
            </tr>
            <tr>
                <th>電話</th>
                <td>076-123-4567</td>
            </tr>
            <tr>
                <th>内容</th>
                <td>スポーツマンシップの精神で日本のスポーツ活性化を目指します。</td>
            </tr>
        </table>

        <h2 class="overview">サービス</h2>
        <div class="service">
            <div class="company_logo">
                <img src="../../public/images/supomalogo.png" alt="">
            </div>
            <div class="company_detail">
                <p>すべてのスポーツマンに捧げるスポーツのマッチングサービス「スポマ」。<br>
                    運動不足を解消して健康な生活をサポートします。
                </p>
            </div>
        </div>

        <h2 class="overview">アクセス</h2>
        <div class="image_area">
            <img src="../../public/images/ai_heart_kokoro_1.png" alt="">
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