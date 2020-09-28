<?php
session_start();
require('./pdo_connect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $dbh->prepare('SELECT * FROM hoot_user WHERE id=?');
    $members->execute(array(
        $_SESSION['id']
    ));
    $member = $members->fetch();

    if (!empty($_POST)) {
        $sth = $dbh->prepare('UPDATE hoot_user SET email=?, password=?, picture=? WHERE id=?');
        $sth->execute(array(
            $_POST['email'],
            sha1($_POST['password']),
            $_POST['owl_color'],
            $_SESSION['id'],
        ));
        header('Location: mypage.php');
        exit();
    } else {
        $error['empty'] = 'empty';
    }
} else {
    header('Location: signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit profile</title>
    <link rel="stylesheet" href="./css/reboot.min.css"/>
    <link rel="stylesheet" href="./css/edit-profile.css"/>
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo"/>
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="signin.html" class="header__signout">ログアウト</a> -->
    </header>
    <div class="main-container">
        <h2 class="page-title">プロフィール編集</h2>
        <div class="profile__info">
            <div class="profile__icon">
                <img src="./icon/<?php print($member['picture']); ?>.svg" alt="icon image">
            </div>
            <div class="profile__mail-and-id">
            </div>
        </div>
        <form class="form" method="post">
            <label for="owl-color" class="form__title">アイコンカラー</label>
            <select name="owl_color" class="form__info" required>
                <option value="owl_blue" selected>ブルー</option>
                <option value="owl_pink">ピンク</option>
                <option value="owl_orange">オレンジ</option>
                <option value="owl_green">グリーン</option>
            </select><br>
            <label for="mail" class="form__title">メールアドレス</label>
            <input id="mail" type="email" class="form__info" name="email" value="<?php print($member['email']);?>" required><br>
            <label for="pw" class="form__title">パスワード</label>
            <input id="pw" type="password" class="form__info" name="password" required><br>
            <label for="re-pw" class="form__title">パスワード(確認用)</label>
            <input id="re-pw" type="password" class="form__info" name="password2" required><br>
            <div class="form__btn-wrapper">
                <button class="form__btn" type="submit">変更</button>
            </div>
        </form>
    </div>
    <button class="back-btn" onclick="location.href='mypage.php'">
        <img src="./icon/arrow.png" alt="arrow image">
    </button>
</div>
</body>
</html>
