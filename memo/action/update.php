<?php
session_start();
require_once('./myUtil.php');
require '../../common/validation.php';
require_once('../../common/database.php');

$id = $_SESSION['user']['id'];

$errors = [];
$gobackURL = "../";

// 文字エンコードの検証
if (!cken($_POST)) {
    header("Location:{$gobackURL}");
    exit();
} else {
    // エスケープ処理
    $_POST = es($_POST);
}

// お名前空チェック
if (empty($_POST['name'])) {
    array_push($errors, "お名前を入力してください");
} else {
    $name = $_POST['name'];
}

// メールアドレス空チェック
if (empty($_POST['email'])) {
    array_push($errors, "メールアドレスを入力してください。");
} else {
    $email = $_POST['email'];
}

// ニックネーム空チェック
if (empty($_POST['nickname'])) {
    array_push($errors, "ニックネームを入力してください。");
} else {
    $nickname = $_POST['nickname'];
}

// スポーツ空チェック
if (empty($_POST['sports'])) {
    array_push($errors, "スポーツの種類を選んでください。");
} else {
    $sports = $_POST['sports'];
}

// 性別空チェック
if (empty($_POST['sex'])) {
    array_push($errors, "性別を選んでください。");
} else {
    $sex = $_POST['sex'];
}

// 年齢空チェック
if (empty($_POST['age'])) {
    array_push($errors, "年齢を入力してください");
} else if (!ctype_digit($_POST['age'])) {
    // 年齢の自然数チェック
    array_push($errors, "年齢の入力内容が間違っています");
} else {
    $age = $_POST['age'];
}


// プロフィール画像の更新
$file = $_FILES['img'];
$file_name = basename($file['name']);
//一時的に保存させれている場所
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];

// ファイルサイズチェック
if ($tmp_path > 4194304 || $file_err === 2) {
    array_push($errors, "ファイルサイズは4MB未満にしてください");
}

//アップロード先
$uplod_dir = '../../images/';
//ファイル名に日付をつけて保存
$save_filename =  date('YmdHis') . $file_name;
$save_path = $uplod_dir . $save_filename;

//拡張子が画像形式かどうか
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
fileExt($_SESSION['errors'], $file_ext, $allow_ext, "画像ファイルを添付してください");

$url = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location:{$gobackURL}");
    exit();
} else {
    $_SESSION['errors'] = [];
}

// プロフィール画像以外の更新作業
// DB接続
try {
    $dbConnect = getDatabaseConnection();
    // トランザクション開始
    $dbConnect->beginTransaction();
    $updateUserinforSql = "UPDATE userinfor SET nickname=:nickname,sports=:sports,sex=:sex,age=:age WHERE user_id=:user_id";
    $updateUserSql = "UPDATE user SET name=:name,email=:email WHERE id=:id";

    $updateUserinforStm = $dbConnect->prepare($updateUserinforSql);
    $updateUserStm = $dbConnect->prepare($updateUserSql);

    $updateUserinforStm->bindValue(':user_id', "$id", PDO::PARAM_INT);
    $updateUserinforStm->bindValue(':nickname', "$nickname", PDO::PARAM_STR);
    $updateUserinforStm->bindValue(':sports', "$sports", PDO::PARAM_STR);
    $updateUserinforStm->bindValue(':sex', "$sex", PDO::PARAM_STR);
    $updateUserinforStm->bindValue(':age', "$age", PDO::PARAM_INT);
    $updateUserStm->bindValue(':id', "$id", PDO::PARAM_INT);
    $updateUserStm->bindValue(':name', "$name", PDO::PARAM_STR);
    $updateUserStm->bindValue(':email', "$email", PDO::PARAM_STR);

    $updateUserStm->execute();
    $updateUserinforStm->execute();

    $dbConnect->commit();
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    $dbConnect->rollBack();
}

//ファイルがあるかどうか
if ($filesize > 0) {
    if (is_uploaded_file($tmp_path)) {
        if (move_uploaded_file($tmp_path, $save_path)) {
            // DB接続
            try {
                // 既存のファイルを取得、削除
                $sql_filename = "SELECT file_path FROM userinfor WHERE user_id=:id";
                $stm_filename = $dbConnect->prepare($sql_filename);
                $stm_filename->bindValue(':id', "$id", PDO::PARAM_INT);
                $stm_filename->execute();
                $dbResult_file = $stm_filename->fetchAll(PDO::FETCH_ASSOC);
                var_dump($dbResult_file);
                $dbfilename = $dbResult_file[0]['file_path'];
                if (unlink($dbfilename)) {
                    $updateFileSql = "UPDATE userinfor SET file_name=:file_name,file_path=:file_path WHERE user_id=:user_id";
                    $updateFileStm = $dbConnect->prepare($updateFileSql);
                    $updateFileStm->bindValue(':user_id', "$id", PDO::PARAM_INT);
                    $updateFileStm->bindValue(':file_path', "$save_path", PDO::PARAM_STR);
                    $updateFileStm->bindValue(':file_name', "$file_name", PDO::PARAM_STR);
                    $updateFileStm->execute();
                    $_SESSION['success'] = "更新完了しました。";
                    header("Location:{$gobackURL}");
                    exit;
                }
            } catch (Exception $e) {
                echo "データベース接続エラーがありました。<br>";
                echo $e->getMessage();
            }
        } else {
            echo "ファイルが保存できませんでした。";
        }
    } else {
        echo 'ファイルが選択されていません';
    }
} else {
    $_SESSION['success'] = "更新完了しました。";
    header("Location:{$gobackURL}");
}
