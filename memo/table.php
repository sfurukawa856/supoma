<?php
session_start();
require '../common/auth.php';
require_once './action/myUtil.php';
require_once('../common/database.php');

if (isset($_SESSION['csrfToken'])) {
    unset($_SESSION['csrfToken']);
}

if (isset($_SESSION['password'])) {
    unset($_SESSION['password']);
}

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}
$id = $_SESSION['user']['id'];


?>
<?php
// search.phpからリダイレクトされたのかチェック
if (!empty($_SESSION['search'])) {
    unset($_SESSION['search']);
    $json = file_get_contents("../public/json/data.json");
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
            file_put_contents("../public/json/data.json", $data, LOCK_EX);
        }

        // プロフィール情報取得
        // 名前情報取得
        $sql = "SELECT name FROM user WHERE id=:id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $nameResult = $stm->fetchAll(PDO::FETCH_ASSOC);
        $name = $nameResult[0]['name'];
        $_SESSION['user_name'] = $name;
        // ファイルパス取得
        $sql = "SELECT nickname,file_path FROM userinfor WHERE user_id=:user_id";
        $stm = $dbConnect->prepare($sql);
        $stm->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stm->execute();
        $filepathResult = $stm->fetchAll(PDO::FETCH_ASSOC);
        $file_name = substr($filepathResult[0]['file_path'], 13);
        $_SESSION['url'] = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";
        $_SESSION['nickname'] = $filepathResult[0]['nickname'];
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
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <?php
    require_once("../common/head.php");
    echo getHeader("一覧ページ");
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/table.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css">
</head>

<body>
    <div class="cursor"></div>
    <div class="follower"></div>
    <?php
    require_once('../common/header.php');
    ?>

    <!-- Slider main container -->
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide1.png" alt="スノーボード">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide2.png" alt="バスケ">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide3.png" alt="テニス">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide4.png" alt="卓球">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide5.png" alt="野球">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide6.png" alt="バドミントン">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide7.png" alt="サッカー">
                </div>
            </div>
            <div class="swiper-slide">
                <div class="img">
                    <img src="../public/images/slide8.png" alt="ダンス">
                </div>
            </div>
        </div>
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
                    <div class="btnhover">
                        <input type="submit" value="検索する" class="searchBtn">
                    </div>
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
                                <input type="hidden" name="end_time" value="<?php echo $result[$i]['end_time']; ?>">
                                <a href="javascript:form<?php echo $i; ?>.submit()">
                                    <div class="image-container">
                                        <img src="<?php echo es(substr($result[$i]['file_path'], 3)); ?>" alt="">
                                    </div>
                                    <div class="table-item-text">
                                        <div class="table-item-text-left">
                                            <p class="category"><?php echo es($result[$i]['category']); ?></p>
                                            <?php
                                            // 日時短縮化
                                            $eventDateShort = substr($result[$i]['eventDate'], 5, -3);
                                            ?>
                                            <p>開催日：<span><?php echo es($eventDateShort); ?></span></p>
                                        </div>
                                        <h3>
                                            <?php
                                            // タイトル12文字以内を表示
                                            if (mb_strlen($result[$i]['title']) >= 12) {
                                                $resultTitle = mb_substr($result[$i]['title'], 0, 11) . "…";
                                            } else {
                                                $resultTitle = $result[$i]['title'];
                                            }
                                            ?>
                                            <?php echo es($resultTitle); ?>
                                        </h3>
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
    <?php
    require '../common/footer.php';
    ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/slider.js"></script>
    <script src="../public/js/readmore.js" type="module"></script>
    <script src="../public/js/script.js"></script>
    <script src="../public/js/end_apply.js" type="module"></script>
    <script src="../public/js/contact.js" type="module"></script>
</body>

</html>