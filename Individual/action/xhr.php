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
// var_dump($post_id);
// var_dump((int)$id);
// var_dump($chatMessage);
// var_dump((int)$commentUserID);
// var_dump((int)$postUserID);

// DB接続
try {
    $dbConnect = getDatabaseConnection();
    $sql = "INSERT INTO chat(chat_id,chat_message,comment_user_id,post_user_id) VALUES (:chat_id,:chat_message,:comment_user_id,:post_user_id)";
    $stm = $dbConnect->prepare($sql);
    // $stm->bindValue(':id', $post_id, PDO::PARAM_INT);
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

// var_dump($_POST);

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
    // echo "ファイル作成完了";
}
