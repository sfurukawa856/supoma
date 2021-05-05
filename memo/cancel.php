<?php
session_start();
require '../common/auth.php';
require_once('./action/myUtil.php');

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../common/head.php");
    echo getHeader("スポマ退会ページ");
    ?>
    <link rel="stylesheet" href="../public/sass/cancel.css">
    <link rel="stylesheet" href="../public/sass/errorMessage.css">
</head>

<body>
    <h2 class="cancel">スポマの退会</h2>
    <hr>
    <main>
        <!-- エラーメッセージ有無チェック -->
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

        <h3 class="title">注意事項</h3>
        <div class="container">
            <p>スポマを退会しますとご登録情報はすべて削除されます。退会を希望される場合は下記より退会手続きを行ってください。</p>
        </div>
        <form action="./action/cancel_check.php" method="post">
            <h3 class="title">退会理由<span class="hissu">※必須　※複数選択可</span></h3>
            <div class="container">
                <input type="checkbox" name="reason[]" id="not_matching" value="マッチングできないから" <?php if (!empty($_SESSION['cancel']['reason'])) {
                                                                                                    checked("マッチングできないから", $_SESSION['cancel']['reason']);
                                                                                                } ?>>
                <label for="not_matching">マッチングできないから</label><br>
                <input type="checkbox" name="reason[]" id="not_sports" value="好きなスポーツが少ないから" <?php if (!empty($_SESSION['cancel']['reason'])) {
                                                                                                    checked("好きなスポーツが少ないから", $_SESSION['cancel']['reason']);
                                                                                                } ?>><label for="not_sports">好きなスポーツが少ないから</label><br>
                <input type="checkbox" name="reason[]" id="not_security" value="セキュリティ面で不安があるから" <?php if (!empty($_SESSION['cancel']['reason'])) {
                                                                                                        checked("セキュリティ面で不安があるから", $_SESSION['cancel']['reason']);
                                                                                                    } ?>><label for="not_security">セキュリティ面で不安があるから</label><br>
                その他<br>
                <textarea name="other"><?php if (!empty($_SESSION['cancel']['other'])) {
                                            echo $_SESSION['cancel']['other'];
                                        } ?></textarea><br>
            </div>
            <div class="final_answer">
                <div class="final_check">
                    <input type="checkbox" class="cancel_checkbox" id="cancel_checkbox">
                    <label for="cancel_checkbox">本当にスポマを退会しますか？</label>
                </div>
                <input type="submit" value="スポマを退会する" class="disabled_submit"><br>
                <a href="./table.php" class="continue">やっぱり続ける</a>
            </div>
        </form>
    </main>

    <?php
    // 退会理由の有無チェック
    if (!empty($_SESSION['cancel'])) {
        unset($_SESSION['cancel']);
    }
    ?>

    <script src="../public/js/cancel.js"></script>
</body>

</html>