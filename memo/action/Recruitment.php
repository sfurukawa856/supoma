<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';

$id = $_SESSION['user']['id'];
// var_dump($id);

$title = $_POST['title'];
$category = $_POST['category'];
$member = $_POST['member'];
$eventDate = $_POST['eventDate'];

// var_dump($eventDate);

$place = $_POST['place'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$message = $_POST['message'];


$file = $_FILES['img'];
// var_dump($file);
$filename = basename($file['name']);
//一時的に保存させれている場所
$tmp_path = $file['tmp_name'];
// echo $tmp_path;
// echo "<br>";
$file_err = $file['error'];
$filesize = $file['size'];
//アップロード先(MANPの場合)
$uplod_dir = '../../images/';

//ファイル名に日付をつけて保存
$save_filename =  date('YmdHis') . $filename;

$save_path = $uplod_dir . $save_filename;



$_SESSION['errors'] = [];

//からチェック
emptyCheck($_SESSION['errors'], $title, "タイトルを入力してください。");
emptyCheck($_SESSION['errors'], $category, "カテゴリーを選択してください");
emptyCheck($_SESSION['errors'], $member, "募集人数を入力してください");
emptyCheck($_SESSION['errors'], $eventDate, "開催日を入力してください");
emptyCheck($_SESSION['errors'], $place, "開催場所を入力してください");
emptyCheck($_SESSION['errors'], $start_time, "募集期間（開始日）を入力してください");
emptyCheck($_SESSION['errors'], $end_time, "募集期間（終了日）を入力してください");
emptyCheck($_SESSION['errors'], $message, "メッセージを入力してください");


if (!$_SESSION['errors']) {
    // - 文字数チェック
    stringMaxSizeCheck($_SESSION['errors'], $title, "タイトルは191文字以内で入力してください。", 191);
    stringMaxSizeCheck($_SESSION['errors'], $place, "開催場所は191文字以内で入力してください。", 191);
    stringMaxSizeCheck($_SESSION['errors'], $place, "メッセージは500文字以内で入力してください。", 500);

    ctypeDigit($_SESSION['errors'], $member, "募集人数は整数で入力してください");

    fileCheck($_SESSION['errors'], $tmp_path, $file_err, "ファイルサイズは1MB未満にしてください");

    dateCheck($_SESSION['errors'], $start_time, '募集期間(開始日)は本日以降を選択してください');
    dateCheck($_SESSION['errors'], $end_time, '募集期間(終了日)は本日以降を選択してください');
    dateCheck2($_SESSION['errors'], $eventDate, '開催日は現在時刻以降を選択してください');
    //拡張子が画像形式かどうか
    $allow_ext = array('jpg', 'jpeg', 'png');
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    fileExt($_SESSION['errors'], $file_ext, $allow_ext, "画像ファイルを添付してください");
}

if ($_SESSION['errors']) {
    header('Location:../apply.php');
    exit;
}


if (is_uploaded_file($tmp_path)) {
    if (move_uploaded_file($tmp_path, $save_path)) {

        $_SESSION['userpost'] = [
            'title' => $title,
            'category' => $category,
            'member' => $member,
            'eventDate' => $eventDate,
            'place' => $place,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'message' => $message,
            'filename' => $filename,
            'tmp_path' => $tmp_path,
            'filename' => $filename,
            'save_filename' => $save_filename,
            'save_path' => $save_path,

        ];

        header('Location:../../Individual/index.php');
        exit;
    } else {
        echo "ファイルが保存できませんでした。";
    };
}
