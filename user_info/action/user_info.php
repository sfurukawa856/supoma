<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';

// CSRF対策
if ($_POST['csrf'] !== $_SESSION['csrfToken']) {
    header('Location: ../../user/index.php');
    exit('もう一度入力してください。');
} else {
    unset($_SESSION['csrfToken']);
}

// セッション変数の変数定義
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$password = $_SESSION['password'];

$nickname = $_POST['nickname'];
$sports = $_POST['sports'];
$sex = $_POST['sex'];
$age = $_POST['age'];

//file
$file = $_FILES['img'];
$filename = basename($file['name']);
//一時的に保存させれている場所
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
//アップロード先(MANPの場合)
$uplod_dir = '../../images/';

//ファイル名に日付をつけて保存
$save_filename =  date('YmdHis') . $filename;

$save_path = $uplod_dir . $save_filename;



$_SESSION['errors'] = [];



//からチェック
emptyCheck($_SESSION['errors'], $nickname, "ニックネームを入力してください。");
emptyCheck($_SESSION['errors'], $sports, "スポーツを選択してください。");
emptyCheck($_SESSION['errors'], $sex, "性別を入力してください。");
emptyCheck($_SESSION['errors'], $age, "年齢を入力してください。");

// - 文字数チェック
stringMaxSizeCheck($_SESSION['errors'], $nickname, "ユーザー名は191文字以内で入力してください。");
stringMaxSizeCheck($_SESSION['errors'], $age, "年齢は11文字以内で入力してください。");

ctypeDigit($_SESSION['errors'], $age, "年齢は整数で入力してください");

fileCheck($_SESSION['errors'], $tmp_path, $file_err, "ファイルサイズは4MB未満にしてください");

//拡張子が画像形式かどうか
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

fileExt($_SESSION['errors'], $file_ext, $allow_ext, "画像ファイルを添付してください");


if ($_SESSION['errors']) {
    header('Location:../../user_info/index.php');
    exit;
}

// DB接続処理
$database_handler = getDatabaseConnection();

$user_sql = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
$userinfor_sql = "INSERT INTO userinfor (user_id, nickname, sports, sex, age, file_name, file_path) VALUES ( :user_id,:nickname, :sports, :sex, :age, :file_name, :file_path)";

// //ファイルがどうか
if (is_uploaded_file($tmp_path)) {
    //保存先を移動(絶対ぱす)
    if (move_uploaded_file($tmp_path, $save_path)) {
        echo $filename . "を" . $uplod_dir . 'にアップしました';
        echo "<br>";

        try {
            $user_stm = $database_handler->prepare($user_sql);
            if ($user_stm) {
                $user_stm->bindValue(':name', htmlspecialchars($user_name));
                $user_stm->bindValue(':email', $user_email);
                $user_stm->bindValue(':password', $password);

                $user_result = $user_stm->execute();

                // ユーザー情報保持
                $_SESSION['user'] = [
                    'name' => $user_name,
                    'id' => $database_handler->lastInsertId()
                ];

                $id = $_SESSION['user']['id'];

                if ($user_result) {
                    $userinfor_stm = $database_handler->prepare($userinfor_sql);
                    if ($userinfor_stm) {
                        $userinfor_stm->bindValue(':nickname', htmlspecialchars($nickname));
                        $userinfor_stm->bindValue(':user_id', $id);
                        $userinfor_stm->bindValue(':sports', $sports);
                        $userinfor_stm->bindValue(':sex', $sex);
                        $userinfor_stm->bindValue(':age', $age);
                        $userinfor_stm->bindValue(':file_name', $filename);
                        $userinfor_stm->bindValue(':file_path', $save_path);

                        $userinfor_result  = $userinfor_stm->execute();

                        $_SESSION['user']['user_id'] = $id;
                    }
                }
            }
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    } else {
        echo "ファイルが保存できませんでした。";
    }
} else {
    echo 'ファイルが選択されていません';
    echo "<br>";
}





// メモ投稿画面にリダイレクト
header('Location: ../../memo/table.php');
exit;
