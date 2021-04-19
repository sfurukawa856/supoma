<?php

session_start();
require '../../common/database.php';
require '../../common/auth.php';
require './myUtil.php';
require '../../common/validation.php';

//ログインユーザー
$id = $_SESSION['user']['id'];

$message = nl2br(es($_POST['message']), false);

$userpost_id = $_POST['user_id'];
$insert_date = $_POST['insert_date'];
$nickname = $_POST['nickname'];

$id_news = $_SESSION['id_news'];

if ($message === "") {
    $message = NULL;
}

unset($_SESSION['id_news']);

// 承認
if (isset($_POST['ok'])) {
    $dbConnect = getDatabaseConnection();

    try {
        $sql = "SELECT title FROM userpost WHERE userpost_id=:id AND insert_date=:insert_date";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', "$id", PDO::PARAM_INT);
        $stm->bindValue(':insert_date', $insert_date, PDO::PARAM_INT);
        $stm->execute();
        $userpostResult = $stm->fetchAll(PDO::FETCH_ASSOC);

        $title = $userpostResult[0]['title'];
    } catch (Exception $e) {

        echo $e->getMessage();
        exit();
    }

    // 残人数を計算するためにnewsテーブルのidからpost_post_idの値をセレクトする
    try {
        $sql = "SELECT post_post_id FROM news WHERE id=:id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', "$id_news", PDO::PARAM_INT);
        $stm->execute();
        $idResult = $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }


    //募集者に通知
    try {

        $sql = "INSERT INTO news (news_id,post_id,post_post_id,post_insert_date,joining_id,approval,count) VALUES ( :news_id,:post_id,:post_post_id,:post_insert_date,:joining_id,:approval, 1)";
        $stm = $dbConnect->prepare($sql);
        if ($stm) {
            $stm->bindValue(':news_id', $id);
            $stm->bindValue(':post_id', $id);
            $stm->bindValue(':post_post_id', $idResult[0]['post_post_id']);
            $stm->bindValue(':post_insert_date', $insert_date);
            $stm->bindValue(':joining_id', $id);
            $stm->bindValue(':approval',  "「" . $title . "」に" . "$nickname" . "さんを参加承認しました");
            $stm->execute();
        }
    } catch (Exception $e) {
        echo  $e->getMessage();
    }


    //応募者のユーザー

    try {
        $sql = "INSERT INTO news (news_id,post_id,post_post_id,post_insert_date,joining_id,result,message,count) VALUES ( :news_id,:post_id,:post_post_id,:post_insert_date,:joining_id,:result, :message,1)";
        $stm = $dbConnect->prepare($sql);
        if ($stm) {
            $stm->bindValue(':news_id', $userpost_id);
            $stm->bindValue(':post_id', $id);
            $stm->bindValue(':post_post_id', $idResult[0]['post_post_id']);
            $stm->bindValue(':post_insert_date', $insert_date);
            $stm->bindValue(':joining_id', $id);
            $stm->bindValue(':result',  "「" . $title . "」に参加承認されました");
            $stm->bindValue(':message', $message);
            $stm->execute();
        }
    } catch (Exception $e) {
        echo  $e->getMessage();
    }
    header('Location: ../../memo/index.php');
    exit;
}



// 却下
if (isset($_POST['no'])) {
    echo "no";

    $dbConnect = getDatabaseConnection();

    try {
        $sql = "SELECT title FROM userpost WHERE userpost_id=:id AND insert_date=:insert_date";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', "$id", PDO::PARAM_INT);
        $stm->bindValue(':insert_date', $insert_date, PDO::PARAM_INT);
        $stm->execute();
        $userpostResult = $stm->fetchAll(PDO::FETCH_ASSOC);

        $title = $userpostResult[0]['title'];
    } catch (Exception $e) {

        echo $e->getMessage();
        exit();
    }

    //募集者に通知
    try {

        $sql = "INSERT INTO news (news_id,post_id,post_insert_date,joining_id,approval,count) VALUES ( :news_id,:post_id,:post_insert_date,:joining_id,:approval, 1)";
        $stm = $dbConnect->prepare($sql);
        if ($stm) {
            $stm->bindValue(':news_id', $id);
            $stm->bindValue(':post_id', $id);
            $stm->bindValue(':post_insert_date', $insert_date);
            $stm->bindValue(':joining_id', $id);
            $stm->bindValue(':approval',  "「" . $title . "」に" . "$nickname" . "さんを参加却下しました");
            $stm->execute();
        }
    } catch (Exception $e) {
        echo  $e->getMessage();
    }




    //応募者のユーザー

    try {
        $sql = "INSERT INTO news (news_id,post_id,post_insert_date,result_no,message,joining_id,count) VALUES ( :news_id,:post_id,:post_insert_date,:result_no,:message,:joining_id, 1)";
        $stm = $dbConnect->prepare($sql);
        if ($stm) {
            $stm->bindValue(':news_id', $userpost_id);
            $stm->bindValue(':post_id', $id);
            $stm->bindValue(':post_insert_date', $insert_date);
            $stm->bindValue(':result_no',  "「" . $title . "」に参加却下されました");
            $stm->bindValue(':message', $message);
            $stm->bindValue(':joining_id', $id);
            $stm->execute();
        }
    } catch (Exception $e) {
        echo  $e->getMessage();
    }
    header('Location: ../../memo/index.php');
    exit;
}
