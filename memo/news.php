<?php
session_start();
require '../common/database.php';
require '../common/auth.php';

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

if (isset($_SESSION['id_news'])) {
    unset($_SESSION['id_news']);
}


$id = $_SESSION['user']['id'];

$dbConnect = getDatabaseConnection();
try {
    $sql = "SELECT * FROM news WHERE news_id=:id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
    exit();
}


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
    require_once("../common/head.php");
    echo getHeader("お知らせページ");
    ?>
    <link rel="stylesheet" href="../public/css/news.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">

</head>

<body>
    <div class="cursor"></div>
    <div class="follower"></div>
    <?php
    require_once('../common/header.php');
    ?>

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

                    $countCheck = (int)$news['count'];


                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        $imgRecruitment = mb_substr($dbResult2[0]['file_path'], 3);
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
                                        <p><a href="javascript:form<?php echo $idNews; ?>.submit()" class="link 
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>"><?php echo $news["recruitment"] ?></a></p>
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

                    $countCheck = (int)$news['count'];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);

                        $imgJoining = mb_substr($dbResult2[0]['file_path'], 3);
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
                                        <p><a href="javascript:form<?php echo $idNews; ?>.submit()" class="link 
                            <?php if ($countCheck === 0) {
                                echo "check";
                            } ?>"><?php echo $news["joining"] ?></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>



                <?php elseif (!empty($news["application"])) : ?>
                    <?php
                    $userpost_id = $news["post_id"];

                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];


                    $take = 'check';

                    $joining_id =  $news["joining_id"];

                    $countCheck = (int)$news['count'];

                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);


                        $imgApplication = mb_substr($dbResult2[0]['file_path'], 3);
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
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>
                                    "><?php echo $news["application"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                <?php elseif (!empty($news["approval"])) : ?>

                    <?php
                    $joining_id =  $news["joining_id"];

                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $idNews = $news["id"];

                    $countCheck = (int)$news['count'];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);


                        $imgApproval = mb_substr($dbResult2[0]['file_path'], 3);
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
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>
                                    "><?php echo $news["approval"] ?></a>
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

                    $insert_date = $news["post_insert_date"];


                    $message = $news['message'];

                    $countCheck = (int)$news['count'];

                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);


                        $imgResult = mb_substr($dbResult2[0]['file_path'], 3);
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
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>
                                    "><?php echo $news["result"] ?></a>
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

                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $countCheck = (int)$news['count'];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);


                        $imgResultNo = mb_substr($dbResult2[0]['file_path'], 3);
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
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>
                                    
                                    "><?php echo $news["result_no"] ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                <?php elseif (!empty($news["chat"])) : ?>

                    <?php
                    $idNews = $news["id"];
                    $joining_id =  $news["joining_id"];

                    $userpost_id = $news["post_id"];
                    $insert_date = $news["post_insert_date"];

                    $countCheck = (int)$news['count'];



                    try {
                        $sql2 = "SELECT * FROM userinfor WHERE user_id = :joining_id";

                        $stm = $dbConnect->prepare($sql2);
                        $stm->bindValue(':joining_id', (int)$joining_id, PDO::PARAM_INT);
                        $stm->execute();
                        $dbResult2 = $stm->fetchAll(PDO::FETCH_ASSOC);


                        $imgResultNo = mb_substr($dbResult2[0]['file_path'], 3);
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
                                            <a href="javascript:form<?php echo $idNews; ?>.submit()" class="link
                                    <?php if ($countCheck === 0) {
                                        echo "check";
                                    } ?>
                                    "><?php echo $news["chat"] ?></a>
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

    <?php
    require '../common/footer.php';
    ?>

    <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js"></script>
    <script src="../public/js/contact.js" type="module"></script>
</body>

</html>