<link rel="stylesheet" href="http://localhost/GroupWork/20210329_spoma-main/public/css/footer.css">

<footer>
    <div class="footer-wrapper">
        <div class="footer-item">
            <h2>Profile</h2>
            <p><a href="http://localhost/GroupWork/20210329_spoma-main/memo/">マイページ</a></p>
            <p><a href="http://localhost/GroupWork/20210329_spoma-main/memo/action/logout.php">ログアウト</a></p>
            <p><a href="http://localhost/GroupWork/20210329_spoma-main/memo/cancel.php">退会する</a></p>
        </div>
        <div class="footer-item">
            <h2>About us</h2>
            <p><a href="">会社概要</a></p>
            <p><a href="">採用情報</a></p>
            <p><a href="">プライバシーポリシー</a></p>
            <p><a href="">スポマ利用規約</a></p>
        </div>
        <div class="footer-item">
            <h2>Help</h2>
            <p><a href="">よくある質問</a></p>
        </div>
    </div>
    <div class="contact">
        <form action="http://localhost/GroupWork/20210329_spoma-main/memo/action/thanks.php" name="contact_form" method="POST">
            <h2>お問い合わせ</h2>
            <p><textarea name="contact" id="contact" cols="30" rows="10" placeholder="スポマに意見を送る..."></textarea></p>
            <input type="submit" value="送信" name="btn_submit" id="btn_submit">
        </form>
    </div>
</footer>