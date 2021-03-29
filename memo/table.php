<?php
session_start();
require '../common/auth.php';
if (!isLogin()) {
    header('Location: ../login/');
    exit;
}
?>
<!-- DB接続 -->
<?php
try {
    require_once('../common/database.php');
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
        file_put_contents("../public/js/data.json", $data);
    }
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
    <link rel="stylesheet" href="../public/css/table.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css">
    <script src="../public/js/mail_validation.js"></script>
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
                        <li><a href="./action/logout.php">ログアウト</a></li>
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
                    <img src="../images/snowbord.jpg" alt="">
                </div>
                <div class="table-item-text">
                    <p class="category">スノーボード</p>
                    <h3>スノーボーダー募集</h3>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../images/tabletennis.webp" alt="">
                </div>
                <div class="table-item-text">
                    <p class="category">卓球</p>
                    <h3>卓球選手募集</h3>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../images/tennis.jpg" alt="">
                </div>
                <div class="table-item-text">
                    <p class="category">テニス</p>
                    <h3>テニスプレイヤー募集</h3>
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
                        <input type="text" name="keyword" placeholder="キーワードで検索">
                    </label>
                    <p>カテゴリー</p>
                    <div class="items">
                        <dd class="dt-r">
                            <select name="category" id="" class="sports">
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
                    </div><br>
                    <input type="submit" value="検索する" class="searchBtn">
                </form>
            </div>
            <div class="table">
                <?php for ($i = 0; $i < count($result); $i++) : ?>
                    <?php if ($i <= 2) : ?>
                        <div class="table-item">
                            <form name="form<?php echo $i; ?>" action="../Individual/personal.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $result[$i]['userpost_id']; ?>">
                                <input type="hidden" name="insert_date" value="<?php echo $result[$i]['insert_date']; ?>">
                                <a href="javascript:form<?php echo $i; ?>.submit()">
                                    <div class="image-container">
                                        <img src="<?php echo substr($result[$i]['file_path'], 3); ?>" alt="">
                                    </div>
                                    <div class="table-item-text">
                                        <div class="table-item-text-left">
                                            <p class="category"><?php echo $result[$i]['category']; ?></p>
                                            <p>開催日：<span><?php echo $result[$i]['eventDate']; ?></span></p>
                                        </div>
                                        <h3><?php echo $result[$i]['title']; ?></h3>
                                    </div>
                                    <p><?php echo $result[$i]['message']; ?></p>
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
                <img src="../images/supomalogo.png" alt="logo" width="100">
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
    <script src="../public/js/readmore.js"></script>
    <script src="../public/js/script.js"></script>
</body>

</html>