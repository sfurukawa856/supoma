<?php
session_start();
require '../common/auth.php';
require_once('./action/myUtil.php');
require_once('../common/database.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}
$id = $_SESSION['user']['id'];
?>
<?php
try {
    $dbConnect = getDatabaseConnection();
    $sql = "SELECT * FROM user,userinfor WHERE id=:id AND user_id=id";
    $stm = $dbConnect->prepare($sql);
    $stm->bindValue(':id', "$id", PDO::PARAM_INT);
    $stm->execute();
    $dbResult = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "データベース接続エラーがありました。<br>";
    echo $e->getMessage();
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

    <head>
        <?php
        //共通ファイル読み込み
        require_once('../common/head.php');
        //head取得
        echo getHeader("基本情報");

        ?>
        <link rel="stylesheet" href="../public/css/main.css">
    </head>

</head>

<body>
    <header class="header">
        <div class="header-wrap">
            <nav class="header-nav">
                <ul>
                    <li>
                        <a href="table.php"> 一覧</a>
                    </li>
                    <?php
                    $name = $dbResult[0]['name'];
                    $_SESSION['user_name'] = $name;
                    ?>
                    <li class="name headerInfo"><?php echo es($name); ?></li>
                    <?php
                    $file_path = $dbResult[0]['file_path'];
                    $path_info = pathinfo($file_path);
                    $file_name = $path_info['basename'];
                    $url = "http://localhost/GroupWork/20210329_spoma-main/images/{$file_name}";

                    $_SESSION['url'] = $url;
                    ?>
                    <li class="navImg headerInfo"><img src="<?php echo $url; ?>" alt=""></li>
                </ul>
            </nav>
            <div class="none">
                <div class="header-mypage">
                    <div class="header-mypage-wrap">
                        <div class="faceName">
                            <div class="img">
                                <img src="<?php echo $url; ?>" alt="">
                            </div>
                            <h1><?php echo es($name); ?></h1>
                        </div>
                        <a href="index.php" class="btn">マイページ</a>
                        <ul class="ul">
                            <li><a href="./table.php">一覧</a></li>
                            <li><a href="./news.php">通知</a></li>
                            <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/apply.php">募集</a></li>
                            <li><a href="./action/logout.php">ログアウト</a></li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-face">
                <div class="img">
                    <img src="<?php echo $url; ?>" alt="">
                </div>
                <h2 class="name"><?php echo es($name); ?></h2>
                <span class="sl">/</span>
                <?php
                $nickname = $dbResult[0]['nickname'];
                $_SESSION['nickname'] = $nickname;
                ?>
                <h3 class="nickname"><?php echo es($nickname); ?></h3>
            </div>

            <ul class="header-item">
                <li><a href="index.php" class="line">基本情報</a></li>
                <li><a href="apply.php">募集する</a></li>
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

    <section class="basic">
        <div class="basic-wrap">
            <div class="basic-gr">
                <h2 class="basic-title">基本情報</h2>
                <!-- エラーメッセージ表示領域 -->
                <?php
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $value) {
                        echo "<p style='color:red;'>{$value}</p>";
                    }
                    unset($_SESSION['errors']);
                }

                if (isset($_SESSION['success'])) {
                    echo "<p style='color:red; margin-bottom:12px;'>{$_SESSION['success']}</p>";
                    $_SESSION['success'] = "";
                    unset($_SESSION['success']);
                }
                ?>
                <form enctype="multipart/form-data" action="./action/update.php" method="POST" class="basic-form">
                    <dl>
                        <div class="items">
                            <dt class="dt-l">お名前</dt>
                            <dd class="dt-r"><input type="text" class="name" name="name" value="<?php echo es($name); ?>"></dd>
                        </div>
                        <div class="items">
                            <dt class="dt-l">メールアドレス</dt>
                            <?php
                            $email = $dbResult[0]['email'];
                            ?>
                            <dd class="dt-r"><input type="email" class="email" name="email" value="<?php echo es($email); ?>"></dd>
                        </div>
                        <div class="items">
                            <dt class="dt-l">ニックネーム</dt>
                            <dd class="dt-r"><input type="text" name="nickname" class="nickname" value="<?php echo es($nickname); ?>"> </dd>
                        </div>
                        <div class="items">
                            <?php
                            $sports = $dbResult[0]['sports'];
                            ?>
                            <dt class="dt-l">スポーツの種類</dt>
                            <dd class="dt-r">
                                <select name="sports" id="" class="sports">
                                    <option value="" disabled selected>選択してください</option>
                                    <option value="サッカー" <?php selected("サッカー", $sports); ?>>サッカー</option>
                                    <option value="野球" <?php selected("野球", $sports); ?>>野球</option>
                                    <option value="テニス" <?php selected("テニス", $sports); ?>>テニス</option>
                                    <option value="スノーボード" <?php selected("スノーボード", $sports); ?>>スノーボード</option>
                                    <option value="バスケットボール" <?php selected("バスケットボール", $sports); ?>>バスケットボール</option>
                                    <option value="ダンス" <?php selected("ダンス", $sports); ?>>ダンス</option>
                                    <option value="バトミントン" <?php selected("バトミントン", $sports); ?>>バトミントン</option>
                                    <option value="卓球" <?php selected("卓球", $sports); ?>>卓球</option>
                                </select>
                            </dd>
                        </div>
                        <div class="items">
                            <?php
                            $sex = $dbResult[0]['sex'];
                            ?>
                            <dt class="dt-l">性別</dt>
                            <dd class="dt-r">
                                <select name="sex" class="sex">
                                    <option value="" disabled selected>選択してください</option>
                                    <option value="男" <?php selected("男", $sex); ?>>男</option>
                                    <option value="女" <?php selected("女", $sex); ?>>女</option>
                                </select>
                            </dd>
                        </div>
                        <div class="items">
                            <?php
                            $age = $dbResult[0]['age'];
                            ?>
                            <dt class="dt-l">年齢</dt>
                            <dd class="dt-r"><input type="number" name="age" class="age" value="<?php echo es($age); ?>"></dd>
                        </div>
                        <div class="items">
                            <dt class="dt-l">プロフィール画像</dt>
                            <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                            <dd class="dt-r"><input name="img" type="file" accept="image/*" / class="file"></dd>
                        </div>
                    </dl>
                    <div class="btn-wrap">
                        <button type="submit" class="btn">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script src="../public/js/script.js"></script>
</body>

</html>