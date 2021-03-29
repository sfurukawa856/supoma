<?php
session_start();
require_once('../common/database.php');

$id = $_SESSION['user']['id'];
require '../common/auth.php';

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}
$name = $_SESSION['user_name'];
$url = $_SESSION['url'];
if (!empty($_SESSION['userpost'])) {
    $title = $_SESSION['userpost']['title'];
    $category = $_SESSION['userpost']['category'];
    $member = $_SESSION['userpost']['member'];
    $eventDate = $_SESSION['userpost']['eventDate'];
    $place = $_SESSION['userpost']['place'];
    $start_time = $_SESSION['userpost']['start_time'];
    $end_time = $_SESSION['userpost']['end_time'];
    $message = $_SESSION['userpost']['message'];
    unset($_SESSION['userpost']);
} else {
    $title = "";
    $category = "";
    $member = "";
    $eventDate = "";
    $place = "";
    $start_time = "";
    $end_time = "";
    $message = "";
}
?>

<?php

//通知数
try {
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT SUM(count) FROM news WHERE news_id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    // 共通ファイル読み込み
    require_once("../common/header.php");
    echo getHeader("マイページ 応募画面");
    ?>
    <link rel="stylesheet" href="../public/css/apply.css">
</head>

<body>
    <header class="header">
        <div class="header-wrap">
            <nav class="header-nav">
                <ul>
                    <li>
                        <a href="table.php"> 一覧</a>
                    </li>
                    <li class="name headerInfo"><?php echo $name; ?>
                    </li>
                    <li class="navImg headerInfo"><img src="<?php echo $url; ?>" alt="">
                    </li>
                </ul>
            </nav>
            <div class="none">
                <div class="header-mypage">
                    <div class="header-mypage-wrap">
                        <div class="faceName">
                            <div class="img">
                                <img src="<?php echo $url; ?>" alt="">
                            </div>
                            <h1><?php echo $name; ?></h1>
                        </div>
                        <a href="./index.php" class="btn">マイページ</a>
                        <ul class="ul">
                            <li><a href="./action/logout.php">ログアウト</a></li>
                            <li><a href="./action/logout.php">ログアウト</a></li>
                            <li><a href="./action/logout.php">ログアウト</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-face">
                <div class="img">
                    <img src="<?php echo $url; ?>" alt="">
                </div>
                <h2 class="name"><?php echo $name; ?></h2>
                <span class="sl">/</span>
                <h3 class="nickname"><?php echo $_SESSION['nickname']; ?></h3>
            </div>

            <ul class="header-item">
                <li><a href="index.php">基本情報</a></li>
                <li><a href="apply.php" class="line">募集する</a></li>
                <li class="news"><a href="news.php">お知らせ</a>
                    <?php if (!empty($dbResult2[0]['SUM(count)'])) : ?>
                    <span class="news-span">
                        <?php echo $dbResult2[0]['SUM(count)']; ?>
                    </span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>

    </header>
    <main class="main">
        <div class="main-wrap">
            <h1 class="topTitle">どんな内容で応募したいですか？</h1>
            <p class="add">※ここで入力された内容は一覧ページに追加されます。</p>
            <?php
            if (isset($_SESSION['errors'])) {
                echo '<div class="alart">';
                foreach ($_SESSION['errors'] as $error) {
                    echo "<p>{$error}</p>";
                }
                echo '</div>';
                unset($_SESSION['errors']);
            }
            ?>
            <?php if (isset($_SESSION['success'])) : ?>
            <p class="success"><?php echo $_SESSION['success'] ?></p>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <form action="./action/Recruitment.php" method="POST" class="main-form" enctype="multipart/form-data">
                <dl>
                    <div class="items">
                        <dt class="dt-l">タイトル</dt>
                        <dd class="dt-r"><input type="text" name="title" id="title" placeholder="（例）フットサル経験者募集"
                                class="titleInput" value="<?php echo $title ?>"></dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">カテゴリー</dt>
                        <dd class="dt-r">
                            <select name="category" id="category" class="category">
                                <option value="" disabled selected>選択してください</option>
                                <option value="サッカー">サッカー</option>
                                <option value="野球">野球</option>
                                <option value="テニス">テニス</option>
                                <option value="スノーボード">スノーボード</option>
                                <option value="バスケットボール">バスケットボール</option>
                                <option value="ダンス">ダンス</option>
                                <option value="バトミントン">バトミントン</option>
                                <option value="卓球">卓球</option>
                            </select>
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">募集人数</dt>
                        <dd class="dt-r">
                            <input type="number" id="number" placeholder="（例）3" class="human" name="member"
                                value="<?php echo $member ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">開催日</dt>
                        <dd class="dt-r">
                            <input type="datetime-local" name="eventDate" id="eventDate" class="eventDate"
                                value="<?php echo $eventDate ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">開催場所</dt>
                        <dd class="dt-r">
                            <input type="text" name="place" id="place" placeholder="（例）代々木公園" class="place"
                                value="<?php echo $place ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">募集期間</dt>
                        <dd class="dt-r applydate">
                            <input type="date" name="start_time" id="period" class="period"
                                value="<?php echo $start_time ?>">
                            <span>~</span>
                            <input type="date" name="end_time" id="period" class="period"
                                value="<?php echo $end_time ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">メッセージ</dt>
                        <dd class="dt-r">
                            <textarea name="message" id="message" class="message"></textarea>
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">画像</dt>
                        <dd class="dt-r">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                            <td class="td-r"><input name="img" type="file" accept="image/*" / class="profile"></td>
                        </dd>
                    </div>
                </dl>
                <div class="btn-wrap">
                    <button type="submit" class="btn">確認画面へ</button>
                </div>
            </form>
        </div>
    </main>
    <script src="../public/js/script.js"></script>
</body>

</html>