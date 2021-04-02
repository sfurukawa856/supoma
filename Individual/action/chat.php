<?php
session_start();

require '../../common/auth.php';
require_once('../../memo/action/myUtil.php');
require_once('../../common/database.php');


if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

var_dump($_POST);
var_dump($_SESSION);

$id = es($_SESSION['user']['id']);

// エスケープ処理、改行処理
$chatMessage = nl2br(es($_POST['message']), false);

try {
    $dbConnect = getDatabaseConnection();
    $sql = "INSERT INTO chat(chat_id,chat_message) VALUES (:chat_id,:chat_message)";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':chat_id', $id, PDO::PARAM_INT);
    $stm->bindValue(':chat_message', $chatMessage, PDO::PARAM_STR);
    $result = $stm->execute();

    if ($result) {
        $stm = $dbConnect->prepare($sql);
        $sql = "SELECT * FROM chat";
        $stm = $dbConnect->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        header("Location: ../personal.php");
    }
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}
