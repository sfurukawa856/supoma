<?php
session_start();
require '../common/database.php';
require '../common/auth.php';

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$id = $_SESSION['user']['id'];

$dbConnect = getDatabaseConnection();
try {
    $sql = "SELECT * FROM news WHERE news_id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($dbResult);
    // echo  $dbResult[0]['recruitment'];
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
function sortByKey($key_name, $sort_order, $array)
{
    foreach ($array as $key => $value) {
        $standard_key_array[$key] = $value[$key_name];
    }

    array_multisort($standard_key_array, $sort_order, $array);

    return $array;
}

if (!empty($dbResult)) {

    $sorted_array = sortByKey('insert_time', SORT_DESC, $dbResult);
}
//降順（insert_timeを基準）


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../common/header.php");
    echo getHeader("お知らせページ");
    ?>
    <link rel="stylesheet" href="../public/css/news.css">
    <script src="../public/js/mail_validation.js"></script>
</head>

<body>
    <header>
        <div class="left flex">
            <a href="./table.php">
                <img src="../public/images/Slogo.png" alt="ロゴ" width="50">
            </a>
            <a href="./apply.php">
                <h2>募集</h2>
            </a>
        </div>
        <div class="right flex">
            <div class="icon"><i class="fas fa-search"></i></div>
            <div class="icon"><i class="fas fa-comment-dots"></i></div>
            <div class="icon"><i class="far fa-bell"></i></div>
            <a href="table.php" class="table">一覧</a>
            <h2 class="headerInfo"><?php echo $_SESSION['user_name']; ?></h2>
            <img src="<?php echo $_SESSION['url']; ?>" alt="プロフィール" width="50" class="headerInfo">
        </div>
        <div class="none">
            <div class="header-mypage">
                <div class="header-mypage-wrap">
                    <div class="faceName">
                        <div class="img">
                            <img src="<?php echo $_SESSION['url']; ?>" alt="">
                        </div>
                        <h1><?php echo $_SESSION['user_name']; ?></h1>
                    </div>
                    <a href="../memo/" class="btn">マイページ</a>
                    <ul class="ul">
                        <li><a href="./action/logout.php">ログアウト</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="title">
            <h2>お知らせ</h2>
        </div>
        <?php if (!empty($dbResult)) : ?>
            <?php foreach ($sorted_array as $news) : ?>

                <?php
                $insert_time = mb_substr($news['insert_time'], 5, 11);
                $replace = str_replace("-", "/", $insert_time);
                ?>

                <?php if (!empty($news["recruitment"])) : ?>
                    <?php
                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];

                    $joining_id =  $news["joining_id"];


                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgRecruitment = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }



                    ?>


                    <form action="../Individual/personal.php" method="POST" name="form<?php echo $idNews; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">

                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgRecruitment; ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p><a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["recruitment"] ?></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                <?php elseif (!empty($news["joining"])) : ?>
                    <?php
                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];


                    $joining_id =  $news["joining_id"];




                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgJoining = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }

                    ?>

                    <form action="../Individual/personal.php" method="POST" name="form<?php echo $idNews; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">

                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgJoining ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p><a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["joining"] ?></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>



                <?php elseif (!empty($news["application"])) : ?>
                    <?php
                    $userpost_id = $news["post_id"];

                    // echo $userpost_id;
                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];


                    $take = 'check';

                    $joining_id =  $news["joining_id"];
                    // echo $joining_id;

                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgApplication = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }

                    ?>

                    <form action="./action/takePart.php" method="POST" name="form<?php echo $idNews; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="bool" value="<?php echo $take; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">


                        <input type="hidden" name="joining_id" value="<?php echo $joining_id; ?>">

                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgApplication ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p>
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["application"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                <?php elseif (!empty($news["approval"])) : ?>

                    <?php
                    $joining_id =  $news["joining_id"];
                    // echo $joining_id;

                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgApproval = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }


                    ?>
                    <form action="../Individual/personal.php" method="POST" name="form<?php echo $idNews; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">
                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgApproval; ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p>
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["approval"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                <?php elseif (!empty($news["result"])) : ?>

                    <?php
                    $idNews = $news["id"];

                    $joining_id =  $news["joining_id"];


                    $userpost_id = $news["post_id"];

                    // echo $userpost_id;
                    $insert_date = $news["post_insert_date"];


                    $message = $news['message'];

                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgResult = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }


                    ?>
                    <form action="./congra.php" method="POST" name="form<?php echo $idNews; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">
                        <input type="hidden" name="userpost_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="message" value="<?php echo $message; ?>">

                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgResult ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p>
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["result"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>

                <?php elseif (!empty($news["result_no"])) : ?>

                    <?php

                    $idNews = $news["id"];
                    $joining_id =  $news["joining_id"];


                    // echo $joining_id;

                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        // var_dump(mb_substr($dbResult2[0]['file_path'], 3));

                        $imgResultNo = mb_substr($dbResult2[0]['file_path'], 3);

                        // var_dump($dbResult2[0]['file_path']);
                    } catch (Exception $e) {
                        echo "データベース接続エラーがありました。<br>";
                        echo $e->getMessage();
                    }


                    ?>
                    <form action="../Individual/personal.php" method="POST" name="form<?php echo $idNews; ?>">

                        <input type="hidden" name="user_id" value="<?php echo $userpost_id; ?>">
                        <input type="hidden" name="insert_date" value="<?php echo $insert_date; ?>">
                        <input type="hidden" name="id_news" value="<?php echo $idNews; ?>">

                        <div class="container">
                            <div class="news">
                                <div class="news-item">
                                    <div class="news-logo">
                                        <img src="<?php echo $imgResultNo; ?>" alt="プロフィール" width="50">
                                    </div>
                                    <div class="news-text">
                                        <p><?php echo $replace ?></p>
                                        <p>
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link"><?php echo $news["result_no"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>
    <footer>
        <div class="footer-wrapper">
            <div class="footer-item">
                <h2>Profile</h2>
                <p><a href="./index.php">マイページ</a></p>
                <p><a href="./action/logout.php">ログアウト</a></p>
            </div>
            <div class="footer-logo">
                <img src="../public//images/supomalogo.png" alt="logo" width="100">
            </div>
        </div>
        <div class="contact">
            <form action="./action/thanks.php" name="contact_form" method="POST" onsubmit="return check()">
                <h2>お問い合わせ</h2>
                <p><textarea name="contact" id="" cols="30" rows="10" placeholder="スポマに意見を送る..."></textarea></p>
                <input type="submit" value="送信" name="btn_submit">
            </form>
        </div>
    </footer>
    <script src="../public/js/script.js"></script>
</body>

</html>