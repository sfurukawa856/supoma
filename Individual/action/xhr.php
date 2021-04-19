<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Document</title>
</head>

<body>

</body>

</html>


<?php
session_start();

require '../../common/auth.php';
require_once('../../memo/action/myUtil.php');
require_once('../../common/database.php');


if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

// 変数定義
$id = es($_SESSION['user']['id']);
// エスケープ処理、改行処理
$chatMessage = nl2br(es($_POST['chat_message']), false);
$commentUserID = $_POST['commentUserID'];
$_SESSION['commentUserID'] = $commentUserID;
$postUserID = $_POST['postUserID'];
$post_id = $_SESSION['post_id'];
//newsテーブルで使用する
$userpost_id = $_POST['userpost_id'];
$insert_date = $_POST['insert_date'];
$title = $_POST['title'];

// jsonファイル書き出し
$data = json_encode($_POST, JSON_UNESCAPED_UNICODE);
try {
    $fileObj = new SplFileObject("../../public/js/chatData.json", "wb");
} catch (Exception $e) {
    echo "ファイルアクセスに失敗しました。<br>";
    echo $e->getMessage();
    exit;
}
$written = $fileObj->fwrite($data);
if ($written === false) {
    echo "ファイル書き込み中に失敗しました。<br>";
} else {
}

// チャット時の通知機能のINSERT（※募集者のみ）

if (!((int)$userpost_id === (int)$id)) {

    try {
        $database_handler = getDatabaseConnection();
        $sql = "INSERT INTO news (news_id, post_id,post_insert_date,chat,joining_id,count) VALUES ( :news_id,:post_id,:post_insert_date,:chat,:joining_id, 1)";
        $stm = $database_handler->prepare($sql);
        if ($stm) {
            $stm->bindValue(':news_id', $userpost_id);
            $stm->bindValue(':post_id', $userpost_id);
            $stm->bindValue(':post_insert_date', $insert_date);
            $stm->bindValue(':chat',  "「" . $title . "」" . "にチャットメッセージがきました");
            $stm->bindValue(':joining_id', $userpost_id);
            $stm->execute();
        }
    } catch (Exception $e) {
        echo  $e->getMessage();
    }
}



// DB接続
try {
    $dbConnect = getDatabaseConnection();
    $sql = "INSERT INTO chat(chat_id,chat_message,comment_user_id,post_user_id) VALUES (:chat_id,:chat_message,:comment_user_id,:post_user_id)";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':chat_id', (int)$id, PDO::PARAM_INT);
    $stm->bindValue(':chat_message', $chatMessage, PDO::PARAM_STR);
    $stm->bindValue(':comment_user_id', (int)$commentUserID, PDO::PARAM_INT);
    $stm->bindValue(':post_user_id', (int)$post_id, PDO::PARAM_INT);
    $result = $stm->execute();
} catch (Exception $e) {
    echo "データベース接続エラーがありました(xhr.php//34)。<br>";
    echo $e->getMessage();
    exit();
}
