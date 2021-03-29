<?php
session_start();
require '../common/auth.php';
//$_SESSION['user']がもっていなかったらログイン画面へ
// if (!isLogin()) {
//     header('Location: ../login/');
//     exit;
// }

//
if (user_id()) {
    header('Location: ../memo/');
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    //共通ファイル読み込み
    require_once('../common/header.php');
    //head取得
    echo getHeader("ユーザー情報登録");

    ?>
    <link rel="stylesheet" href="../public/css/user_info.css">
</head>

<body>
    <div class="body">
        <div>

            <h1 class="title">最初に、プロフィールを充実させましょう</h1>
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
            <p class="subtitle">ここで入力する情報は、マイページに表示されます。別途設定することが可能です。</p>
            <form enctype="multipart/form-data" action="./action/user_info.php" method="POST">

                <table>
                    <tr>
                        <td class="td-l">ニックネーム</td>
                        <td class="td-r"><input type="text" name="nickname" class="nickname"></td>
                    </tr>
                    <tr>
                        <td class="td-l">スポーツの種類を選んでください</td>
                        <td class="td-r">
                            <select name="sports" id="" class="sports">
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
                        </td>
                    </tr>
                    <tr>
                        <td class="td-l">性別</td>
                        <td class="td-r">
                            <select name="sex" class="sex">
                                <option value="" disabled selected>選択してください</option>
                                <option value="男">男</option>
                                <option value="女">女</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-l">年齢</td>
                        <td class="td-r"><input type="number" name="age" class="age"></td>
                    </tr>
                    <tr>
                        <td class="td-l">プロフィール画像</td>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                        <td class="td-r"><input name="img" type="file" accept="image/*" / class="profile"></td>
                    </tr>
                </table>
                <div class="btn">
                    <button type="submit">次へ</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>