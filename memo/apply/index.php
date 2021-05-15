<?php
session_start();
require_once('../../common/database.php');
require_once('../action/myUtil.php');

$id = $_SESSION['user']['id'];
require '../../common/auth.php';

if (!isLogin()) {
    header('Location: ../../login/');
    exit;
}
$name = $_SESSION['user_name'];
$url = $_SESSION['url'];
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
    require_once("../../common/head.php");
    echo getHeader("マイページ 応募画面");
    ?>
    <link rel="stylesheet" href="../../public/css/apply.css">
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
                            <li><a href="./table/">一覧</a></li>
                            <li><a href="./news/">通知</a></li>
                            <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/apply/">募集</a></li>
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
                <li><a href="../">基本情報</a></li>
                <li><a href="<?php $_SERVER['SCRIPT_NAME']; ?>" class="line">募集する</a></li>
                <li class="news"><a href="../news/">お知らせ</a>
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
            <p class="hissu">*は必須です。</p>
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
            <?php
            if (!empty($_SESSION['userpost'])) {
                $title = $_SESSION['userpost']['title'];
                $category = $_SESSION['userpost']['category'];
                $member = $_SESSION['userpost']['member'];
                $eventDate = $_SESSION['userpost']['eventDate'];
                $eventEndDate = $_SESSION['userpost']['eventEndDate'];
                $place = $_SESSION['userpost']['place'];
                $start_time = $_SESSION['userpost']['start_time'];
                $end_time = $_SESSION['userpost']['end_time'];
                $message = strip_tags($_SESSION['userpost']['message']);
                unset($_SESSION['userpost']);
                echo "<p class='hissu'>※画像項目は必ずご確認ください。</p>";
            } else {
                $title = "";
                $category = "";
                $member = "";
                $eventDate = "";
                $eventEndDate = "";
                $place = "";
                $start_time = "";
                $end_time = "";
                $message = "";
            }
            ?>

            <!-- CSRF対策 -->
            <?php
            if (!isset($_SESSION['csrfToken'])) {
                $csrfToken = bin2hex(random_bytes(32));
                $_SESSION['csrfToken'] = $csrfToken;
            }
            $token = $_SESSION['csrfToken'];
            ?>

            <?php if (isset($_SESSION['success'])) : ?>
                <p class="success"><?php echo $_SESSION['success'] ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <form action="./action/Recruitment.php" method="POST" class="main-form" enctype="multipart/form-data">
                <dl>
                    <div class="items">
                        <dt class="dt-l">タイトル<span class="kome">*</span></dt>
                        <dd class="dt-r"><input type="text" name="title" id="title" placeholder="（例）フットサル経験者募集" class="titleInput" value="<?php echo $title ?>"></dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">カテゴリー<span class="kome">*</span></dt>
                        <dd class="dt-r">
                            <select name="category" id="category" class="category">
                                <option value="" disabled selected>選択してください</option>
                                <option value="サッカー" <?php selected("サッカー", $category); ?>>サッカー</option>
                                <option value="野球" <?php selected("野球", $category); ?>>野球</option>
                                <option value="テニス" <?php selected("テニス", $category); ?>>テニス</option>
                                <option value="スノーボード" <?php selected("スノーボード", $category); ?>>スノーボード</option>
                                <option value="バスケットボール" <?php selected("バスケットボール", $category); ?>>バスケットボール</option>
                                <option value="ダンス" <?php selected("ダンス", $category); ?>>ダンス</option>
                                <option value="バトミントン" <?php selected("バトミントン", $category); ?>>バトミントン</option>
                                <option value="卓球" <?php selected("卓球", $category); ?>>卓球</option>
                                <option value="ゴルフ" <?php selected("ゴルフ", $category); ?>>ゴルフ</option>
                            </select>
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">募集人数<span class="kome">*</span></dt>
                        <dd class="dt-r">
                            <input type="number" id="number" placeholder="（例）3" class="human" name="member" value="<?php echo $member ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">開催日時<span class="kome">*</span></dt>
                        <dd class="dt-r eventdate">
                            <input type="datetime-local" name="eventDate" id="period" class="period event" value="<?php echo $eventDate ?>">
                            <span>~</span>
                            <input type="datetime-local" name="eventEndDate" id="period" class="period event_end" value="<?php echo $eventEndDate ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">開催場所<span class="kome">*</span></dt>
                        <dd class="dt-r">
                            <input type="text" name="place" id="place" placeholder="（例）代々木公園" class="place" value="<?php echo $place ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">募集期間<span class="kome">*</span></dt>
                        <dd class="dt-r applydate">
                            <input type="date" name="start_time" id="period" class="period" value="<?php echo $start_time ?>">
                            <span>~</span>
                            <input type="date" name="end_time" id="period" class="period" value="<?php echo $end_time ?>">
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">メッセージ<span class="kome">*</span></dt>
                        <dd class="dt-r">
                            <textarea name="message" id="message" class="message"><?php echo $message; ?></textarea>
                        </dd>
                    </div>
                    <div class="items">
                        <dt class="dt-l">画像<span class="kome">*</span></dt>
                        <dd class="dt-r">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                            <td class="td-r"><input name="img" type="file" accept="image/*" / class="profile"></td>
                        </dd>
                    </div>
                    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
                </dl>
                <div class="btn-wrap">
                    <button type="submit" class="btn">確認画面へ</button>
                </div>
            </form>
        </div>
    </main>
    <script src="../../public/js/script.js"></script>
    <script src="../../public/js/applydate.js"></script>
</body>

</html>