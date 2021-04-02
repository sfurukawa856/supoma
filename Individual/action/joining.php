<?php
session_start();
require '../../common/database.php';

//ログインユーザーのID
$id = $_SESSION['user']['id'];
$title = $_SESSION['title'];
//投稿者にID
$userpost_id = $_SESSION['userpost_id'];
$insert_date = $_SESSION['insert_date'];



// var_dump($userpost_id);

// var_dump($title);
unset($_SESSION['title']);
unset($_SESSION['userpost_id']);
unset($_SESSION['insert_date']);

//応募者側のnewsテーブルにインサート
$database_handler = getDatabaseConnection();
try {
    $sql = "INSERT INTO news (news_id, post_id,post_insert_date,joining,joining_id,count) VALUES ( :news_id,:post_id,:post_insert_date,:joining,:joining_id, 1)";
    $stm = $database_handler->prepare($sql);
    if ($stm) {
        $stm->bindValue(':news_id', $id);
        $stm->bindValue(':post_id', $userpost_id);
        $stm->bindValue(':post_insert_date', $insert_date);
        $stm->bindValue(':joining',  "「" . $title . "」" . "に参加申請しました");
        $stm->bindValue(':joining_id', $id);
        $stm->execute();
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}



//募集者側のnewsテーブルにインサート
try {
    $sql2 = "INSERT INTO news (news_id, post_id,post_insert_date, joining_id,application,count) VALUES ( :news_id,:post_id,:post_insert_date,:joining_id,:application, 1)";
    $stm2 = $database_handler->prepare($sql2);
    if ($stm2) {
        $stm2->bindValue(':news_id', $userpost_id);
        $stm2->bindValue(':post_id', $userpost_id);
        $stm2->bindValue(':post_insert_date', $insert_date);
        $stm2->bindValue(':joining_id', $id);
        $stm2->bindValue(':application',  "「" . $title . "」" . "に応募されました");
        $stm2->execute();
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}





//募集者側のnewsテーブルにインサート
// try {
//     // $database_handler = getDatabaseConnection();
//     $sql2 = "INSERT INTO news (news_id, joining,count) VALUES ( :news_id,:joining, 1)";
//     $stm2 = $database_handler->prepare($sql2);
//     if ($stm2) {
//         $stm2->bindValue(':news_id', $userpost_id);
//         $stm2->bindValue(':joining',  "「" . $title . "」" . "に応募されました");
//         $stm2->execute();
//     }
// } catch (Exception $e) {
//     echo  $e->getMessage();
// }



// 一覧投稿画面にリダイレクト
header('Location:../../memo/table.php');
exit;