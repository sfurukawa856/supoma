<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';
require_once './myUtil.php';

var_dump($_SESSION);
var_dump($_POST);

// CSRF対策
if ($_POST['csrf'] !== $_SESSION['csrfToken']) {
    header('Location: ../../user/index.php');
    exit('もう一度入力してください。');
} else {
    unset($_SESSION['csrfToken']);
}

$id = $_SESSION['user']['id'];

$title = es($_POST['title']);
$category = es($_POST['category']);
$member = es($_POST['member']);
$eventDate = es($_POST['eventDate']);
$eventEndDate = es($_POST['eventEndDate']);
$place = es($_POST['place']);
$start_time = es($_POST['start_time']);
$end_time = es($_POST['end_time']);
$message = nl2br(es($_POST['message']), false);


$file = es($_FILES['img']);
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
emptyCheck($_SESSION['errors'], $title, "タイトルを入力してください。");
emptyCheck($_SESSION['errors'], $category, "カテゴリーを選択してください");
emptyCheck($_SESSION['errors'], $member, "募集人数を入力してください");
emptyCheck($_SESSION['errors'], $eventDate, "開催日時を入力してください");
emptyCheck($_SESSION['errors'], $eventEndDate, "開催日時を入力してください");
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

    fileCheck($_SESSION['errors'], $tmp_path, $file_err, "ファイルサイズは4MB未満にしてください");

    dateCheck($_SESSION['errors'], $start_time, '募集期間(開始日)は本日以降を選択してください');
    dataCheck3($_SESSION['errors'], $end_time, $start_time, '募集期間(終了日)は募集開始日以降を選択してください');
    dateCheck2($_SESSION['errors'], $eventDate, '開催日は現在時刻以降を選択してください');
    //拡張子が画像形式かどうか
    $allow_ext = array('jpg', 'jpeg', 'png');
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    fileExt($_SESSION['errors'], $file_ext, $allow_ext, "画像ファイルを添付してください");
}


$_SESSION['userpost'] = [
    'title' => $title,
    'category' => $category,
    'member' => $member,
    'eventDate' => $eventDate,
    'eventEndDate' => $eventEndDate,
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

if ($_SESSION['errors']) {
    header('Location:../apply.php');
    exit;
}

if (is_uploaded_file($tmp_path)) {
    if (move_uploaded_file($tmp_path, $save_path)) {

        unset($_SESSION['collect']);

        header('Location:../../Individual/index.php');
        exit;
    } else {
        echo "ファイルが保存できませんでした。";
    };
}
