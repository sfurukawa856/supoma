<?php

session_start();
require '../../../common/database.php';
require '../../../common/auth.php';


if (!isLogin()) {
    header('Location:../../../login/');
    exit;
}

$check = $_POST['bool'];

if (!$check === "check") {

    header('Location: ../../../memo/');
    exit;
}

$id = $_SESSION['user']['id'];



//応募者のID
//自分の募集IDになっている。
$user_id = $_POST['user_id'];


$_SESSION['userId'] = $user_id;
//応募者のnewsID countを更新するために必要
$id_news = $_POST['id_news'];
// 残人数を計算するためにセッションに入れてcheck.phpに渡す
$_SESSION['id_news'] = $id_news;

//募集投稿者ID

$_SESSION['joining_id'] = $_POST['joining_id'];
$joining_id = $_POST['joining_id'];


//投稿期日
$insert_date = $_POST['insert_date'];







$dbConnect = getDatabaseConnection();

try {
    $sql = "SELECT nickname,sex,file_path,age FROM userinfor WHERE user_id = :user_id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':user_id', (int)$joining_id, PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

$nickname = $dbResult[0]['nickname'];
$sex = $dbResult[0]['sex'];
$file_path = $dbResult[0]['file_path'];
$age = $dbResult[0]['age'];

$url = $file_path;


//newsテーブルのcountカラム更新

if (!empty($_POST['id_news'])) {
    $idNews = $_POST['id_news'];

    try {

        $sql2 = "UPDATE news SET count = 0 WHERE id = :id";

        $stm2 = $dbConnect->prepare($sql2);
        $stm2->bindValue(':id', "$idNews", PDO::PARAM_INT);
        $stm2->execute();
    } catch (Exception $e) {
        echo "データベース接続エラーがありました。<br>";
        echo $e->getMessage();
    }
}


?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    //共通ファイル読み込み
    require_once('../../../common/head.php');
    //head取得
    echo getHeader("申請承認画面");
    ?>
    <link rel="icon" href="../../../public/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../../../public/images/apple-touch-icon.png">
    <link rel="stylesheet" type="text/css" href="../../../public/css/rest.css" />
    <link rel="stylesheet" href="../../../public/css/takePart.css">
</head>

<body>


    <?php
    require_once('../../../common/header.php');
    ?>

    <main class="main">

        <h1><?php echo $nickname ?>さんから参加申請がありました。<br>承認手続きをしてください。</h1>
        <ul>
            <li><img src="<?php echo $url ?>" alt="" width="50"></li>
            <li>
                <h2>名前 : <?php echo $nickname ?></h2>
            </li>
            <li>
                <h2>性別 : <?php echo $sex ?></h2>
            </li>
            <li>
                <h2>年齢 : <?php echo $age ?></h2>
            </li>
        </ul>

        <form action="./check.php" method="POST">
            <div class="meg__g">
                <h4 class="message__txt">メッセージを贈ろう(任意)</h4>

                <p class="al">メッセージを入力してください(500文字以内)</p>
                <textarea name="message" id="" class="message"></textarea>
            </div>
            <div class="btn">
                <input type="hidden" name="user_id" value="<?php echo $joining_id; ?>">
                <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                <input type="hidden" name="nickname" value="<?php echo $nickname; ?>">
                <p><input type="submit" name="ok" value="承認" class="btnCheck"></p>
                <p><input type="submit" name="no" value="却下" class="btnCheck"></p>
            </div>
        </form>
    </main>
    //
    <!-- <script src="../../../public/js/jquery-3.6.0.min.js"></script> -->

    <script src="../../../public/js/script.js"></script>
</body>

</html>