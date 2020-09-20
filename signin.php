<?php
session_start();
require('./pdo_connect.php');

if (!empty($_POST)) {
    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        $login = $dbh->prepare('SELECT * FROM hoot_user WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();

        if ($member) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            header('Location: index.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="./css/reboot.min.css">
    <link rel="stylesheet" href="./css/signin.css">
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo">
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="#" class="header__signout">ログアウト</a> -->
    </header>
    <div class="main-container">
        <h1 class="title">ログイン</h1>
        <form class="form" method="post" action="signin.php">
            <label for="mail" class="form__title">メールアドレス</label>
            <input id="mail" type="email" class="form__info" name="email" required><br>
            <label for="pw" class="form__title">パスワード</label>
            <input id="pw" type="password" class="form__info" name="password" required><br>
            <?php if ($error['login'] === 'failed') : ?>
                <p class="col-sm-10">ログインに失敗しました。正しくご記入ください</p>
            <?php endif; ?>
            <div class="form__btn-wrapper">
                <button class="form__btn">ログイン</button>
                <a href="signup.php" class="form__signup">新規登録</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>