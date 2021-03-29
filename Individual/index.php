<?php
session_start();

require '../common/auth.php';
require_once('../memo/action/myUtil.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

// if (isset($_SESSION['recruitment'])) {
//     unset($_SESSION['recruitment']);
//     header('Location: ../memo/');
//     exit;
// };

$id = $_SESSION['user']['id'];

$title = $_SESSION['userpost']['title'];
$category = $_SESSION['userpost']['category'];
$member = $_SESSION['userpost']['member'];
$eventDate = $_SESSION['userpost']['eventDate'];
// var_dump($eventDate);

$replace = str_replace("T", " ", $eventDate);
$replace2 = str_replace("-", "/", $replace);
// var_dump($replace2);

$place = $_SESSION['userpost']['place'];
$start_time = $_SESSION['userpost']['start_time'];
$startTime  = str_replace("-", "/", mb_substr($start_time, 5));

$end_time = $_SESSION['userpost']['end_time'];
$endTime  = str_replace("-", "/", mb_substr($end_time, 5));

$message = $_SESSION['userpost']['message'];
$filename = $_SESSION['userpost']['filename'];
$tmp_path = $_SESSION['userpost']['tmp_path'];
$save_path = $_SESSION['userpost']['save_path'];
$save_filename = $_SESSION['userpost']['save_filename'];


?>

<?php

//userinfoのデータ取得
try {
    require_once('../common/database.php');
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT * FROM user,userinfor WHERE id=:id AND user_id=id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($dbResult);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("../common/header.php");
    echo getHeader("募集個別確認ページ");
    ?>
    <link rel="stylesheet" href="../public/css/Individual.css">
</head>

<body>
    <header>
        <div class="left flex">
            <img src="../images/Slogo.png" alt="ロゴ" width="50">
            <h2>募集</h2>
        </div>
        <div class="right flex">
            <div class="icon"><i class="fas fa-search"></i></div>
            <div class="icon"><i class="fas fa-comment-dots"></i></div>
            <div class="icon"><i class="far fa-bell"></i></div>
            <?php
            $name = $dbResult[0]['name'];
            ?>
            <h2 class="headerInfo"><?php echo es($name); ?></h2>
            <?php
            $file_path = $dbResult[0]['file_path'];
            $path_info = pathinfo($file_path);
            $file_name = $path_info['basename'];
            // $url = "http://localhost/GroupWork/20210319spoma-miyamura/images/{$file_name}";
            $url = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";
            // "http://localhost/";
            ?>
            <img src="<?php echo $url; ?>" alt="プロフィール" width="50" class="headerInfo">
        </div>
        <div class="none">

            <div class="header-mypage">
                <div class="header-mypage-wrap">
                    <div class="faceName">
                        <div class="img">
                            <img src="<?php echo $url; ?>" alt="">
                        </div>
                        <h1><?php echo es($name); ?></h1>
                    </div>
                    <a href="../memo/" class="btn">マイページ</a>
                    <ul class="ul">
                        <li><a href="./action/logout.php">ログアウト</a></li>
                        <li><a href="./action/logout.php">ログアウト</a></li>
                        <li><a href="./action/logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>



        </div>
    </header>



    <main class="main">


        <?php

        $url2 = "http://localhost/GroupWork/20210329_spoma-main/images/{$save_filename}";
        // "http://localhost/";

        ?>

        <div class="main-sp-img">
            <img src="<?php echo $url2; ?>" alt="">
        </div>
        <div class="main-wrap">

            <span class="main-category"><?php echo es($category); ?></span>
            <h1 class="main-title"><?php echo es($title); ?></h1>
            <p class="main-eventdate">開催日 <time datetime="<?php echo mb_substr($replace2, 5, 11); ?>">
                    <?php echo mb_substr($replace2, 5, 11); ?>~</time></p>
            <div class="main-top-img">
                <img src="<?php echo $url2; ?>" alt="">
            </div>
            <div class="flex">
                <div class="flex-l">
                    <dl class="main-items">
                        <div class="main-items-wrap">
                            <dt class="item">募集期間</dt>
                            <dd class="answer">
                                <?php
                                echo $startTime; ?>~<?php echo $endTime; ?>
                            </dd>
                        </div>
                        <div class="main-items-wrap">
                            <dt class="item">募集人数</dt>
                            <dd class="answer"><?php echo $member; ?>人</dd>
                        </div>
                        <div class="main-items-wrap">
                            <dt class="item">開催場所</dt>
                            <dd class="answer"><?php echo es($place); ?></dd>
                        </div>
                        <dt class="message">メッセージ</dt>
                        <dd class="text"><?php echo es($message); ?></dd>
                    </dl>

                    <form action="#" method="POST" class="form">
                        <h2 class="comment">コメント欄<span class="notification">※応募者が質問、コメントする欄になります</span></h2>
                        <p class="info">相手のことを考え丁寧なコメントを心がけましょう</p>
                        <!-- <div class="talk-items-user">
                            <div class="block">
                                <p class="nickname">ニックネーム</p>
                                <p class="talk">testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest</p>
                            </div>
                            <div class="img-wrap"><img src="../images/Slogo.png" alt=""></div>
                        </div>
                        <div class="talk-items-my">
                            <div class="img-wrap"><img src="../images/Slogo.png" alt=""></div>
                            <div class="block">
                                <p class="nickname">ニックネーム</p>
                                <p class="talk">testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest</p>
                            </div>
                        </div>
                        <div class="talk-items-my">
                            <div class="img-wrap"><img src="../images/Slogo.png" alt=""></div>
                            <div class="block">
                                <p class="nickname">ニックネーム</p>
                                <p class="talk">testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest</p>
                            </div>
                        </div>
                        <div class="talk-items-user">
                            <div class="block">
                                <p class="nickname">ニックネーム</p>
                                <p class="talk">testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest</p>
                            </div>
                            <div class="img-wrap"><img src="../images/Slogo.png" alt=""></div>
                        </div> -->
                        <textarea name="message" class="message" placeholder="質問する..."></textarea>
                        <div class="form-btn">
                            <button type="submit">質問する</button>
                        </div>
                    </form>



                </div>


                <div class="main-btn-wrap">
                    <div class="main-btn-wrap-flex">
                        <a href="./action/collect.php" class="return" class="main-btn">確定</a>
                        <a href="./action/imgRemove.php" class="return">修正する</a>
                        <div class="side-block">
                            <h2>開催者</h2>
                            <?php
                            $nickname = $dbResult[0]['nickname'];
                            ?>
                            <p><?php echo es($nickname); ?></p>
                            <h2>評価</h2>
                            <p>★★★★</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-transparent">
        </div>
    </main>
    <hr>
    <footer>
        <div class="footer-wrapper">
            <div class="footer-item">
                <h2>About</h2>
                <p>会社概要</p>
            </div>
            <div class="footer-item">
                <h2>Profile</h2>
                <p>マイページ</p>
                <p>設定</p>
                <p>ログアウト</p>
            </div>
            <div class="footer-item">
                <h2>Language</h2>
                <p>日本語</p>
                <p>English</p>
            </div>
            <div class="footer-logo">
                <img src="../images/supomalogo.png" alt="logo" width="100">
            </div>
        </div>
        <div class="contact">
            <h2>お問い合わせ</h2>
            <p><textarea name="contact" id="" placeholder="スポマに意見を送る..."></textarea></p>
            <input type="submit" value="送信">
        </div>
    </footer>

    <script src="../public/js/script.js"></script>
</body>

</html>