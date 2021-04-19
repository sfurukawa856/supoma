<?php
session_start();

require '../common/auth.php';
require_once('../memo/action/myUtil.php');
require_once('../common/database.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$id = es($_SESSION['user']['id']);
$userpost_id = es($_POST['user_id']);
$insert_date = es($_POST['insert_date']);

$_SESSION['userpost_id'] = $userpost_id;
$_SESSION['insert_date'] = $insert_date;

?>


<?php

//newsテーブルのcountカラム更新

if (!empty($_POST['id_news'])) {
    $idNews = es($_POST['id_news']);
    $dbConnect = getDatabaseConnection();

    try {

        $sql = "UPDATE news SET count = 0 WHERE id = :id";

        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', "$idNews", PDO::PARAM_INT);
        $stm->execute();
    } catch (Exception $e) {
        echo "データベース接続エラーがありました。<br>";
        echo $e->getMessage();
    }
}

?>

<?php

//ログインしているユーザーのuserinfoのデータ取得
try {
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT * FROM user,userinfor WHERE id=:id AND user_id=id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $userinfoResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました(personal.php//62)。<br>";
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
} catch (Exception $e) {
    echo "データベース接続エラーがありました(personal.php//50)。<br>";
    echo $e->getMessage();
    exit();
}
$commentUserNickname = $userinfoResult[0]['nickname'];
$nickname = $postUserinfoResult[0]['nickname'];

//投稿者のuserpostのデータ取得
try {
    $sql = "SELECT * FROM userpost WHERE userpost_id=:id AND insert_date=:insert_date";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$userpost_id", PDO::PARAM_INT);
    $stm->bindValue(':insert_date', $insert_date, PDO::PARAM_INT);
    $stm->execute();
    $userpostResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました(personal.php//69)。<br>";
    echo $e->getMessage();
    exit();
}

//通知数
try {
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
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <?php
    require_once("../common/head.php");
    echo getHeader("募集個別ページ");
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/Individual2.css">
</head>

<body>
    <div class="cursor"></div>
    <div class="follower"></div>
    <?php
    require_once('../common/header.php');
    ?>

    <!-- <header>
        <div class="left flex">
            <a href="../memo/table.php">
                <img src="../public/images/Slogo.png" alt="ロゴ" width="50">
            </a>
            <a href="../memo/apply.php">
                <h2>募集</h2>
            </a>
        </div>
        <div class="right flex">
            <div class="icon">


                <a href="../memo/news.php">
                    <i class="far fa-bell"></i>
                </a>

                <?php if (!empty($dbResult2[0]['SUM(count)'])) : ?>
                    <span class="news-span">
                        <?php echo $dbResult2[0]['SUM(count)']; ?>
                    </span>
                <?php endif; ?>

            </div>
            <?php
            $name = $userinfoResult[0]['name'];
            ?>
            <h2 class="headerInfo"><?php echo es($name); ?></h2>
            <?php
            $file_path = $userinfoResult[0]['file_path'];
            $path_info = pathinfo($file_path);
            $file_name = $path_info['basename'];
            $url = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";
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
                        <li><a href="../memo/table.php">一覧</a></li>
                        <li><a href="../memo/news.php">通知</a></li>
                        <li><a href="../memo/action/logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header> -->

    <main class="main">

        <?php
        $file_path2 = $userpostResult[0]['file_path'];
        $path_info2 = pathinfo($file_path2);
        $file_name2 = $path_info2['basename'];
        $url2 = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name2}";
        ?>
        <div class="main-sp-img">
            <img src="<?php echo $url2; ?>" alt="">
        </div>
        <div class="main-wrap">
            <?php
            $userpost_id1 = $userpostResult[0]['userpost_id'];
            $category = $userpostResult[0]['category'];
            $title = $userpostResult[0]['title'];
            $_SESSION['title'] = $title;
            $datetime = $userpostResult[0]['eventDate'];
            $eventDate = mb_substr($userpostResult[0]['eventDate'], 5, 11);
            $start_time = mb_substr($userpostResult[0]['start_time'], 5);
            $end_time = mb_substr($userpostResult[0]['end_time'], 5);
            $member = $userpostResult[0]['member'];
            $place = $userpostResult[0]['place'];
            $message = $userpostResult[0]['message'];
            $post_id = $userpostResult[0]['post_id'];
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
                                <input type="hidden" class="end_time" value="<?php echo $userpostResult[0]['end_time']; ?>">
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
                        <dd class="text"><?php echo $message; ?></dd>
                    </dl>

                    <form method="POST" class="form">
                        <h2 class="comment">コメント欄</h2>
                        <p class="info">相手のことを考え丁寧なコメントを心がけましょう</p>
                        <!-- チャットエリア -->
                        <?php
                        $_SESSION['post_id'] = $post_id;
                        // chatテーブルから募集ページのIDを使って検索
                        $chatSql = "SELECT * FROM chat WHERE post_user_id=:post_id";
                        $chatStm = $dbConnect->prepare($chatSql);
                        $chatStm->bindValue(':post_id', $post_id, PDO::PARAM_INT);
                        $chatStm->execute();
                        $chatResult = $chatStm->fetchAll(PDO::FETCH_ASSOC);

                        // userinforテーブルからchatテーブルのコメントユーザーIDを使って検索
                        $sql = "SELECT * FROM userinfor WHERE user_id=:user_id";
                        $stm = $dbConnect->prepare($sql);
                        $userinforResults = [];
                        for ($j = 0; $j < count($chatResult); $j++) {
                            $stm->bindValue(':user_id', $chatResult[$j]['comment_user_id'], PDO::PARAM_INT);
                            $stm->execute();
                            $userinforResult = $stm->fetchAll(PDO::FETCH_ASSOC);
                            array_push($userinforResults, $userinforResult);
                        }

                        // userpostテーブルから$POST_idを使って検索
                        $postSql = "SELECT * FROM userpost WHERE post_id=:post_id";
                        $postStm = $dbConnect->prepare($postSql);
                        $postStm->bindValue(':post_id', $post_id, PDO::PARAM_INT);
                        $postStm->execute();
                        $postResult = $postStm->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="commentArea">
                            <?php if (!empty($chatResult)) : ?>
                                <?php for ($i = 0; $i < count($chatResult); $i++) : ?>
                                    <?php if ($postResult[0]['userpost_id'] === $chatResult[$i]['comment_user_id']) : ?>
                                        <div class="talk-items-my">
                                            <div class="img-wrap">
                                                <img src="<?php echo substr($userinforResults[$i][0]['file_path'], 3) ?>">
                                            </div>
                                            <div class="block">
                                                <p class="nickname"><?php echo $userinforResults[$i][0]['nickname'] ?></p>
                                                <?php
                                                $chatDate = substr($chatResult[$i]['chat_date'], 5, -3);
                                                $chatDate = str_replace("-", "/", $chatDate);
                                                ?>
                                                <p class="talk"><?php echo $chatResult[$i]['chat_message'] . "<br><span class='chatDate'>" . $chatDate . "</span>" ?></p>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="talk-items-user">
                                            <div class="block">
                                                <p class="nickname"><?php echo $userinforResults[$i][0]['nickname'] ?></p>
                                                <?php
                                                $chatDate = substr($chatResult[$i]['chat_date'], 5, -3);
                                                $chatDate = str_replace("-", "/", $chatDate);
                                                ?>
                                                <p class="talk"><?php echo $chatResult[$i]['chat_message'] . "<br><span class='chatDate'>" . $chatDate . "</span>" ?></p>
                                            </div>
                                            <div class="img-wrap">
                                                <img src="<?php echo substr($userinforResults[$i][0]['file_path'], 3) ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            <?php endif; ?>

                            <div class="talk-items-user none">
                                <div class="block">
                                    <p class="nickname"></p>
                                    <p class="talk"></p>
                                </div>
                                <div class="img-wrap">
                                </div>
                            </div>
                            <div class="talk-items-my none">
                                <div class="img-wrap">
                                </div>
                                <div class="block">
                                    <p class="nickname"></p>
                                    <p class="talk"></p>
                                </div>
                            </div>
                        </div>
                        <textarea name="chat_message" class="message" id="message" placeholder="コメントする..."></textarea>
                        <input type="hidden" name="commentUserID" value="<?php echo $id; ?>">
                        <input type="hidden" name="postUserID" value="<?php echo $userpost_id1; ?>">
                        <input type="hidden" name="commentUserFilepath" value="<?php echo $file_path; ?>">
                        <input type="hidden" name="postUserFilepath" value="<?php echo $file_path2; ?>">
                        <input type="hidden" name="commentUserNickname" value="<?php echo $commentUserNickname; ?>">
                        <input type="hidden" name="postUserNickname" value="<?php echo $nickname; ?>">

                        <!-- newsでテーブルで必要 -->
                        <!-- 投稿者のID -->
                        <input type="hidden" name="userpost_id" value="<?php echo $userpost_id; ?>">
                        <!-- 投稿した時間 -->
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="title" value="<?php echo $title; ?>">

                        <div class="form-btn">
                            <button type="button" id="submit">コメントする</button>
                        </div>
                    </form>
                </div>

                <div class="main-btn-wrap">
                    <div class="main-btn-wrap-flex">
                        <?php
                        // 募集内容に対して承認されたユーザーIDを検索しプッシュ
                        $array_id = [];
                        $joinSql = "SELECT news_id FROM news WHERE post_post_id=:post_post_id AND result IS NOT NULL";
                        $joinStm = $dbConnect->prepare($joinSql);
                        $joinStm->bindValue(':post_post_id', $post_id, PDO::PARAM_INT);
                        $joinStm->execute();
                        $joinResult = $joinStm->fetchAll(PDO::FETCH_ASSOC);
                        for ($i = 0; $i < count($joinResult); $i++) {
                            array_push($array_id, $joinResult[$i]['news_id']);
                        }
                        ?>
                        <?php if ($id === $userpost_id || in_array($id, $array_id)) : ?>
                            <a href="./action/joining.php" class="myself">参加する</a>

                        <?php else : ?>
                            <a href="./action/joining.php" class="main-btn">参加する</a>
                        <?php endif; ?>
                        <?php
                        // 残り人数カウントエリア
                        $newsSql = "SELECT post_id,approval FROM news WHERE post_post_id=:post_post_id AND approval IS NOT NULL";
                        $newsStm = $dbConnect->prepare($newsSql);
                        $newsStm->bindValue(':post_post_id', $post_id, PDO::PARAM_INT);
                        $newsStm->execute();
                        $newsResult = $newsStm->fetchAll(PDO::FETCH_ASSOC);
                        if ($member - count($newsResult) > 0) {
                            echo "<p class='member'>残り" . ($member - count($newsResult)) . "人！</p>";
                        } else {
                            echo "<p class='member-end'>満員になりました。</p>";
                        }
                        ?>
                        <div class="side-block">
                            <h2>開催者</h2>
                            <p><?php echo es($nickname); ?><br><span class="review">★★★☆☆</span></p>
                            <h2>参加者</h2>
                            <?php
                            // 検索されたユーザーIDを使ってニックネームをセレクト
                            for ($i = 0; $i < count($joinResult); $i++) {
                                $joinNicknameSql = "SELECT user_id,nickname FROM userinfor WHERE user_id=:user_id";
                                $joinNicknameStm = $dbConnect->prepare($joinNicknameSql);
                                $joinNicknameStm->bindValue(':user_id', $joinResult[$i]['news_id'], PDO::PARAM_INT);
                                $joinNicknameStm->execute();
                                $joinNicknameResult = $joinNicknameStm->fetchAll(PDO::FETCH_ASSOC);
                                array_push($array_id, $joinNicknameResult[0]['user_id']);
                                echo "<p>" . $joinNicknameResult[0]['nickname'] . " さん</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-transparent">
        </div>
    </main>
    <hr>
    <?php
    require '../common/footer.php';
    ?>

    <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js"></script>
    <script src="../public/js/chat.js" type="module"></script>
    <script src="../public/js/end_personal.js" type="module"></script>
    <script src="../public/js/contact.js" type="module"></script>

</body>

</html>