<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    require_once("../head.php");
    echo getHeader("お問い合わせページ");
    ?>
    <link rel="stylesheet" href="../../public/css/contact.css">
</head>

<body>
    <?php
    require_once('../../common/header.php');
    ?>


    <main>
        <h2 class="contact">お問い合わせ</h2>

        <?php
        if (!empty($_SESSION['errors'])) {
            echo "<p class='errorMsg'>" . $_SESSION['errors'][0] . "</p>";
            unset($_SESSION['errors']);
        }
        ?>
        <form action="../contact_check/" method="post" class="form">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>氏名<span class="hissu">※</span></th>
                        <td>
                            <input type="text" name="name" class="name" value="<?php if (!empty($_SESSION['name'])) {
                                                                                    echo $_SESSION['name'];
                                                                                    unset($_SESSION['name']);
                                                                                }  ?>" placeholder="山田太郎">
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス<span class="hissu">※</span></th>
                        <td>
                            <input type="email" name="email" class="email" value="<?php if (!empty($_SESSION['email'])) {
                                                                                        echo $_SESSION['email'];
                                                                                        unset($_SESSION['email']);
                                                                                    }  ?>" placeholder="sample@sample.jp">
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>
                            <input type="tel" name="tel" class="tel" value="<?php if (!empty($_SESSION['tel'])) {
                                                                                echo $_SESSION['tel'];
                                                                                unset($_SESSION['tel']);
                                                                            }  ?>" placeholder="03-1234-5678">
                        </td>
                    </tr>
                    <tr>
                        <th>お問い合わせ内容<span class="hissu">※</span></th>
                        <td>
                            <textarea name="content" class="content" placeholder="お問い合わせ内容を入力してください。"><?php if (!empty($_SESSION['content'])) {
                                                                                                            echo $_SESSION['content'];
                                                                                                            unset($_SESSION['content']);
                                                                                                        }  ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="attention">※がついているものは記入必須です。</p>
            <p class="submit_area">
                <input type="submit" value="確認画面へ">
            </p>
        </form>
    </main>
    <hr>
    <?php
    require_once('../footer.php');
    ?>

    <script src="../../public/js/jquery-3.6.0.min.js"></script>
    <script src="../../public/js/script.js"></script>

</body>

</html>