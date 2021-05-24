<?php
session_start();

require '../common/auth.php';
require_once('../memo/action/myUtil.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$id = es($_SESSION['user']['id']);
$userpost_id = es($_POST['user_id']);
$insert_date = es($_POST['insert_date']);

// var_dump($_POST);
// var_dump($_SESSION);
?>

<?php

//ログインしているユーザーのuserinfoのデータ取得
try {
    require_once('../common/database.php');
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT * FROM user,userinfor WHERE id=:id AND user_id=id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $userinfoResult = $stm->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($userinfoResult);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}

// 投稿者のuserinforのデータ取得
try {
    $sql = "SELECT nickname FROM userinfor WHERE user_id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$userpost_id", PDO::PARAM_INT);
    $stm->execute();
    $postUserinfoResult = $stm->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($postUserinfoResult);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}

//投稿者のuserpostのデータ取得
try {
    $sql = "SELECT * FROM userpost WHERE userpost_id=:id AND insert_date=:insert_date";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$userpost_id", PDO::PARAM_INT);
    $stm->bindValue(':insert_date', $insert_date, PDO::PARAM_INT);
    $stm->execute();
    $userpostResult = $stm->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($userpostResult);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}

//
/**
 * 多次元配列のソート関数
 * @param string $key_name
 * @param $sort_order
 * @param array $array
 * @return array $array
 */
// function sortByKey($key_name, $sort_order, $array)
// {
//     foreach ($array as $key => $value) {
//         $standard_key_array[$key] = $value[$key_name];
//     }

//     array_multisort($standard_key_array, $sort_order, $array);

//     return $array;
// }
//降順（insert_dateを基準）
// $sorted_array = sortByKey('insert_date', SORT_DESC, $userpostResult);
// var_dump($sorted_array);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("../common/header.php");
    echo getHeader("募集個別ページ");
    ?>
    <link rel="stylesheet" href="../public/css/Individual2.css">
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
            $name = $userinfoResult[0]['name'];
            ?>
            <h2 class="headerInfo"><?php echo es($name); ?></h2>
            <?php
            $file_path = $userinfoResult[0]['file_path'];
            $path_info = pathinfo($file_path);
            $file_name = $path_info['basename'];
            $url = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";
            // $url = "http://localhost/supoma-locall/images/{$file_name}";
            ?>
            <img src="<?php echo $url; ?>" alt="プロフィール" width="50" class="headerInfo">
        </div>
        <div class="none">

            <div class="header-mypage">
                <div class="header-mypage-wrap">
                    <div class="faceName">
                        <div class="img">
                            <img src="
                            <?php echo $url; ?>" alt="">
                        </div>
                        <h1><?php
                            echo es($name);
                            ?></h1>
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
        $file_path2 = $userpostResult[0]['file_path'];
        $path_info2 = pathinfo($file_path2);
        $file_name2 = $path_info2['basename'];
        $url2 = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name2}";
        // $url2 = "http://localhost/supoma-locall/images/{$file_name2}";
        ?>

        <div class="main-sp-img">
            <img src="<?php echo $url2; ?>" alt="">
        </div>
        <div class="main-wrap">
            <?php

            $category = $userpostResult[0]['category'];
            $title = $userpostResult[0]['title'];
            $datetime = $userpostResult[0]['eventDate'];
            $eventDate = mb_substr($userpostResult[0]['eventDate'], 5, 11);
            $start_time = mb_substr($userpostResult[0]['start_time'], 5);
            $end_time = mb_substr($userpostResult[0]['end_time'], 5);
            $member = $userpostResult[0]['member'];
            $place = $userpostResult[0]['place'];
            $message = $userpostResult[0]['message'];



            ?>
            <span class="main-category"><?php echo es($category); ?></span>
            <h1 class="main-title"><?php echo es($title); ?></h1>
            <p class="main-eventdate">開催日 <time datetime="<?php echo $datetime; ?>">
                    <?php echo $eventDate; ?>~</time></p>
            <div class="main-top-img">
                <img src="<?php echo $url2; ?>" alt="">
            </div>
            <div class="flex">
                <div class="flex-l">
                    <dl class="main-items">
                        <div class="main-items-wrap">
                            <dt class="item">募集期間</dt>
                            <dd class="answer">
                                <?php echo str_replace("-", "/", $start_time); ?>~<?php echo str_replace("-", "/", $end_time); ?>
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
                        <h2 class="comment">コメント欄</h2>
                        <p class="info">相手のことを考え丁寧なコメントを心がけましょう</p>
                        <div class="talk-items-user">
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
                        </div>
                        <textarea name="message" class="message" placeholder="質問する..."></textarea>
                        <div class="form-btn">
                            <button type="submit">質問する</button>
                        </div>
                    </form>

                </div>


                <div class="main-btn-wrap">
                    <div class="main-btn-wrap-flex">
                        <button type="submit" class="main-btn">参加する</button>
                        <div class="side-block">
                            <h2>開催者</h2>
                            <?php
                            $nickname = $postUserinfoResult[0]['nickname'];
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