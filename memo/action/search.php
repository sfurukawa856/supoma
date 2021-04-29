<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';
require './myUtil.php';

// エスケープ処理
if (!empty($_POST['keyword'])) {
    $keyword = es($_POST['keyword']);
    $_SESSION['keyword'] = $keyword;
}
if (!empty($_POST['category'])) {
    $category = es($_POST['category']);
    $_SESSION['category'] = $category;
}

if (isset($keyword) || isset($category)) {
    // DB接続
    try {
        $dbConnect = getDatabaseConnection();
        if (isset($keyword) === true && isset($category) === false) {
            $sql = "SELECT * FROM userpost WHERE CONCAT(title,category,member,eventDate,insert_date,place,start_time,end_time,message,update_time) LIKE :keyword";
            $stm = $dbConnect->prepare($sql);
            $stm->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        } else if (isset($category) === true && isset($keyword) === false) {
            $sql = "SELECT * FROM userpost WHERE category=:category";
            $stm = $dbConnect->prepare($sql);
            $stm->bindValue(':category', $category, PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT * FROM userpost WHERE category=:category OR CONCAT(title,category,member,eventDate,insert_date,place,start_time,end_time,message,update_time) LIKE :keyword";
            $stm = $dbConnect->prepare($sql);
            $stm->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
            $stm->bindValue(':category', $category, PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach ($result as $key => $value) {
            $updated[$key] = $value['update_time'];
        }

        //配列のkeyのupdatedでソート
        array_multisort($updated, SORT_DESC, $result);

        // jsonファイルに書き出し
        $data = json_encode($result, JSON_UNESCAPED_UNICODE);

        try {
            $fileObj = new SplFileObject("../../public/json/data.json", "wb");
        } catch (Exception $e) {
            echo "ファイルアクセスに失敗しました。<br>";
            echo $e->getMessage();
            exit;
        }
        $written = $fileObj->fwrite($data);
        if ($written === false) {
            echo "ファイル書き込み中に失敗しました。<br>";
        } else {
            $_SESSION['search'] = "検索完了";
            header('Location:../table.php');
        }
    } catch (Exception $e) {
        echo "データベース接続エラーがありました。<br>";
        echo $e->getMessage();
        exit;
    }
} else {
    header('Location:../table.php');
}
