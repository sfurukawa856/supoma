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
        $stm->bindValue(':message', htmlspecialchars($message));
        $stm->bindValue(':file_name', $filename);
        $stm->bindValue(':file_path', $save_path);
        $result  = $stm->execute();

        $_SESSION['recruitment'] = "募集完了しました";
        $recruitment = $_SESSION['recruitment'];
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}


$sql2 = "INSERT INTO news (news_id, recruitment,count) VALUES ( :news_id,:recruitment,1)";

try {

    $stm = $database_handler->prepare($sql2);

    if ($stm) {
        $stm->bindValue(':news_id', $id);
        $stm->bindValue(':recruitment', $recruitment);
        $result  = $stm->execute();
    }
} catch (Exception $e) {
    echo  $e->getMessage();
}


// 一覧投稿画面にリダイレクト
header('Location:../../memo/table.php');
exit;