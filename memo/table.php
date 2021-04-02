<?php
session_start();
require '../common/auth.php';
require_once './action/myUtil.php';
require_once('../common/database.php');


if (!isLogin()) {
    header('Location: ../login/');
    exit;
}
// var_dump($_SESSION);
$id = $_SESSION['user']['id'];


?>
<?php
// search.phpからリダイレクトされたのかチェック
if (!empty($_SESSION['search'])) {
    unset($_SESSION['search']);
    $json = file_get_contents("../public/js/data.json");
    $result = json_decode($json, true);
} else {
    // DB接続
    try {
        $dbConnect = getDatabaseConnection();
        $sql = "SELECT * FROM userpost";
        $stm = $dbConnect->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $updated[$key] = $value['update_time'];
            }

            //配列のkeyのupdatedでソート
            array_multisort($updated, SORT_DESC, $result);

            // jsonファイルに書き出し
            $data = json_encode($result, JSON_UNESCAPED_UNICODE);
            file_put_contents("../public/js/data.json", $data, LOCK_EX);
        }
    } catch (Exception $e) {
        echo "データベース接続エラーがありました。<br>";
        echo $e->getMessage();
    }
}
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
    require_once("../common/header.php");
    echo getHeader("一覧ページ");
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/table.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css">
    <script src="../public/js/mail_validation.js"></script>
</head>

<body>
    <header>
        <div class="left flex">
            <a href="table.php"><img src="../public/images/Slogo.png" alt="ロゴ" width="50"></a>
            <h2><a href="apply.php">募集</a></h2>
        </div>
        <div class="right flex">
            <div class="icon">
                <a href="./news.php"><i class="far fa-bell"></i></a>

                <!-- <span class="news-span">10</span> -->

                <?php if (!empty($dbResult2[0]['SUM(count)'])) : ?>
                    <span class="news-span">
                        <?php echo $dbResult2[0]['SUM(count)']; ?>
                    </span>
                <?php endif; ?>

            </div>
            <h2 class="headerInfo"><?php echo es($_SESSION['user_name']); ?></h2>
            <img src="<?php echo es($_SESSION['url']); ?>" alt="プロフィール" width="50" class="headerInfo">
        </div>

        <div class="none">
            <div class="header-mypage">
                <div class="header-mypage-wrap">
                    <div class="faceName">
                        <div class="img">
                            <img src="<?php echo es($_SESSION['url']); ?>" alt="">
                        </div>
                        <h1><?php echo es($_SESSION['user_name']); ?></h1>
                    </div>
                    <a href="../memo/" class="btn">マイページ</a>
                    <ul class="ul">
                        <li><a href="./action/logout.php">ログアウト</a></li>
                    </ul>
                </div>
            </div>



        </div>

    </header>

    <!-- Slider main container -->
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/snow.jpg" alt="スノーボード">
                    <p class="Snowbord">Snowbord</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/basuke.jpg" alt="バスケ">
                    <p class="Basketball">Basketball</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/tenisu.jpg" alt="テニス">
                    <p class="Tennis">Tennis</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/takkyu.jpg" alt="卓球">
                    <p class="Tabletennis">Tabletennis</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/baseball.jpg" alt="野球">
                    <p class="Baseball">Baseball</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/badominton.jpg" alt="バドミントン">
                    <p class="Badminton">Badminton</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/soccer.jpg" alt="サッカー">
                    <p class="Soccer">Soccer</p>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/dansu.jpg" alt="ダンス">
                    <p class="Dance">Dance</p>
                </div>
            </div>
        </div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <main>
        <div class="main-container">
            <div class="conditions">
                <form action="./action/search.php" method="post">
                    <label>
                        <p>検索条件</p>
                        <input type="text" name="keyword" placeholder="キーワードで検索" value="<?php echo $_SESSION['keyword'] ?>">
                    </label>
                    <p>カテゴリー</p>
                    <div class="items">
                        <dd class="dt-r">
                            <select name="category" id="" class="sports">
                                <option value="" disabled selected>選択してください</option>
                                <option value="サッカー" <?php selected("サッカー", $_SESSION['category']); ?>>サッカー</option>
                                <option value="野球" <?php selected("野球", $_SESSION['category']); ?>>野球</option>
                                <option value="テニス" <?php selected("テニス", $_SESSION['category']); ?>>テニス</option>
                                <option value="スノーボード" <?php selected("スノーボード", $_SESSION['category']); ?>>スノーボード</option>
                                <option value="バスケットボール" <?php selected("バスケットボール", $_SESSION['category']); ?>>バスケットボール</option>
                                <option value="ダンス" <?php selected("ダンス", $_SESSION['category']); ?>>ダンス</option>
                                <option value="バトミントン" <?php selected("バトミントン", $_SESSION['category']); ?>>バトミントン</option>
                                <option value="卓球" <?php selected("卓球", $_SESSION['category']); ?>>卓球</option>
                            </select>
                        </dd>
                    </div><br>
                    <input type="submit" value="検索する" class="searchBtn">
                </form>
            </div>
            <?php
            if (isset($_SESSION['keyword'])) {
                unset($_SESSION['keyword']);
            }
            if (isset($_SESSION['category'])) {
                unset($_SESSION['category']);
            }
            ?>
            <div class="table">
                <?php for ($i = 0; $i < count($result); $i++) : ?>
                    <?php if ($i <= 2) : ?>
                        <div class="table-item">
                            <form name="form<?php echo $i; ?>" action="../Individual/personal.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $result[$i]['userpost_id']; ?>">
                                <input type="hidden" name="insert_date" value="<?php echo $result[$i]['insert_date']; ?>">
                                <a href="javascript:form<?php echo $i; ?>.submit()">
                                    <div class="image-container">
                                        <img src="<?php echo es(substr($result[$i]['file_path'], 3)); ?>" alt="">
                                    </div>
                                    <div class="table-item-text">
                                        <div class="table-item-text-left">
                                            <p class="category"><?php echo es($result[$i]['category']); ?></p>
                                            <p>開催日：<span><?php echo es($result[$i]['eventDate']); ?></span></p>
                                        </div>
                                        <h3><?php echo es($result[$i]['title']); ?></h3>
                                    </div>
                                    <?php
                                    // メッセージ120文字以内を表示
                                    if (mb_strlen($result[$i]['message']) >= 120) {
                                        $resultMessage = mb_substr($result[$i]['message'], 0, 119) . "…";
                                    } else {
                                        $resultMessage = $result[$i]['message'];
                                    }
                                    ?>
                                    <p><?php echo $resultMessage; ?></p>
                                </a>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>

        <div class="number">
            <p><button id="readmore">もっと見る</button></p>
        </div>
        <div class="bg-transparent">
        </div>
    </main>
    <hr>
    <footer>
        <div class="footer-wrapper">
            <div class="footer-item">
                <h2>Profile</h2>
                <p><a href="./index.php">マイページ</a></p>
                <p><a href="./action/logout.php">ログアウト</a></p>
            </div>
            <div class="footer-logo">
                <img src="../public/images/supomalogo.png" alt="logo" width="100">
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
    <script src="../public/js/slider.js"></script>
    <script src="../public/js/readmore.js" type="module"></script>
    <script src="../public/js/script.js"></script>
</body>

</html>