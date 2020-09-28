<?php
session_start();
require 'pdo_connect.php';
//$sth = $dbh->prepare('INSERT INTO hoot_user(name, gender, email, password) VALUES(?, ?, ?, ?)');
//$sth->execute(array('rikuta', 'men', 'rikuta@gmail.com', 'LLkoO89K'));
if (!empty($_POST)) {
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }

    //アカウントの重複をチェック
    if (empty($error)) {
        $member = $dbh->prepare('SELECT COUNT(*) AS cnt FROM hoot_user WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }

    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location:register_sql_user.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./css/reboot.min.css">
    <link rel="stylesheet" href="./css/signup.css">
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo">
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="#" class="header__signout">ログアウト</a> -->
    </header>
    <div class="main-logo">
        <img src="./icon/hoot_main_logo.svg" alt="logo image">
    </div>
    <div class="main-container">
        <form class="form" method="post" action="signup.php">
            <label for="email" class="form__title">メールアドレス</label>
            <input id="email" type="email" class="form__info" name="email"><br>
            <?php if ($error['email'] === 'duplicate') : ?>
                <p>指定されたメールアドレスは、すでに登録されています。</p>
            <?php endif; ?>

            <label for="password" class="form__title">パスワード</label>
            <input id="password" type="password" class="form__info" name="password"><br>
            <label for="password2" class="form__title">パスワード(確認用)</label>
            <input id="password2" type="password" class="form__info" name="password2"><br>
            <?php if ($error['password'] === 'length') : ?>
                <p>パスワードは4文字以上で入力してください。</p>
            <?php endif; ?>
            <div class="form__btn-wrapper">
                <button class="form__btn">登録</button>
                <!-- <a href="signup.html" class="form__signup">新規登録</a> -->
            </div>
        </form>
    </div>
</div>
</body>
</html>