<?php
session_start();
require_once('./action/myUtil.php');
require_once('../common/database.php');


$id = $_SESSION['user']['id'];

try {
    $dbConnect = getDatabaseConnection();
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
        $sql = "UPDATE userinfor SET nickname='退会済みユーザー',sex=Null,age=Null,file_name='Slogo.png',file_path='...../public/images/Slogo.png' WHERE user_id=:user_id";
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
    echo "データベース接続エラーがありました。<br>";
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