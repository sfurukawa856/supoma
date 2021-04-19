<?php
session_start();
require '../../common/database.php';

$id = $_SESSION['user']['id'];

$title = $_SESSION['userpost']['title'];
$category = $_SESSION['userpost']['category'];
$member = $_SESSION['userpost']['member'];
$eventDate = $_SESSION['userpost']['eventDate'];
$place = $_SESSION['userpost']['place'];
$start_time = $_SESSION['userpost']['start_time'];
$end_time = $_SESSION['userpost']['end_time'];

$message = $_SESSION['userpost']['message'];
$filename = $_SESSION['userpost']['filename'];
$tmp_path = $_SESSION['userpost']['tmp_path'];
$filename = $_SESSION['userpost']['filename'];
$save_path = $_SESSION['userpost']['save_path'];

unset($_SESSION['userpost']);

// DB接続処理
$database_handler = getDatabaseConnection();

$sql = "INSERT INTO userpost (userpost_id, title, category, member, eventDate, place, start_time, end_time, message, file_name, file_path) VALUES ( :userpost_id, :title, :category, :member, :eventDate, :place, :start_time, :end_time, :message, :file_name, :file_path)";



//保存先を移動(絶対ぱす)
try {

    $stm = $database_handler->prepare($sql);

    if ($stm) {
        $stm->bindValue(':userpost_id', $id);
        $stm->bindValue(':title', htmlspecialchars($title));
        $stm->bindValue(':category', $category);
        $stm->bindValue(':member', $member);
        $stm->bindValue(':eventDate', $eventDate);
        $stm->bindValue(':place', htmlspecialchars($place));
        $stm->bindValue(':start_time', $start_time);
        $stm->bindValue(':end_time', $end_time);
        $stm->bindValue(':message', $message);
        $stm->bindValue(':file_name', $filename);
        $stm->bindValue(':file_path', $save_path);
        $result  = $stm->execute();



        $_SESSION['recruitment'] = "「" . $title . "」" . "募集完了しました";
        $recruitment = $_SESSION['recruitment'];

        if ($result) {

            $sql3 = "SELECT insert_date FROM userpost WHERE userpost_id = :userpost_id AND file_path = :file_path";

            $stm3 = $database_handler->prepare($sql3);
            $stm3->bindValue(':userpost_id', $id);
            $stm3->bindValue(':file_path', $save_path);
            $stm3->execute();
            $dbResult = $stm3->fetchAll(PDO::FETCH_ASSOC);

            $post_insert_date = $dbResult[0]['insert_date'];
        }
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}




//newsテーブルに追加
$sql2 = "INSERT INTO news (news_id, post_id,post_insert_date,recruitment,	joining_id,count) VALUES ( :news_id,:post_id,:post_insert_date,:recruitment,:joining_id,1)";


try {

    $stm2 = $database_handler->prepare($sql2);

    if ($stm2) {
        $stm2->bindValue(':news_id', $id);
        $stm2->bindValue(':post_id', $id);
        $stm2->bindValue(':post_insert_date', $post_insert_date);
        $stm2->bindValue(':recruitment', $recruitment);
        $stm2->bindValue(':joining_id', $id);
        $result  = $stm2->execute();
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}

$_SESSION['collect']  = [
    'check' => true
];


// 一覧投稿画面にリダイレクト
header('Location:../../memo/table.php');
exit;
