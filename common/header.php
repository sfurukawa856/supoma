<link rel="stylesheet" href="http://localhost/GroupWork/20210329_spoma-main/public/css/header.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
<header>
    <div class="left flex">
        <a href="http://localhost/GroupWork/20210329_spoma-main/memo/table/">
            <img src="http://localhost/GroupWork/20210329_spoma-main/public/images/Slogo.png" alt="ロゴ" width="50">
            <h2 class="supoma">スポマ</h2>
        </a>
    </div>

    <div class="right flex">
        <div class="icon">
            <a href="http://localhost/GroupWork/20210329_spoma-main/memo/news/"><i class="far fa-bell"></i></a>
            <?php if (!empty($dbResult2[0]['SUM(count)'])) : ?>
                <span class="news-span">
                    <?php echo $dbResult2[0]['SUM(count)']; ?>
                </span>
            <?php endif; ?>
        </div>
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
                <a href="http://localhost/GroupWork/20210329_spoma-main/memo/" class="btn">マイページ</a>
                <ul class="ul">
                    <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/table/">一覧</a></li>
                    <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/news/">通知</a></li>
                    <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/apply/">募集する</a></li>
                    <li><a href="http://localhost/GroupWork/20210329_spoma-main/memo/action/logout.php">ログアウト</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>