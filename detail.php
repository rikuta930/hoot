<?php
session_start();
require './pdo_connect.php';

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();


    ##音声再生のための記述
    $listen = 0;
    $hoot_sound_id = $_GET['hoot_sound_id'];
    $user_id = $_GET['user_id'];
    $sound = $dbh->prepare('SELECT * FROM hoot_sound WHERE id=? and user_id=? and listen=?');
    $sound->execute(array(
        $hoot_sound_id,
        $user_id,
        $listen,
    ));
    $data = $sound->fetch();
    $filename = $data['name'];

    ##ハッシュタグを表示
    $hoot_hashtag_id = $data['hoot_hashtag_id'];
    $hashtags = $dbh->prepare('select * from hoot_hashtag where id = ?');
    $hashtags->execute(array(
        $hoot_hashtag_id,
    ));
    $hashtag_data = $hashtags->fetch();

    ##画像を表示
    $members = $dbh->prepare('SELECT * FROM hoot_user WHERE id=?');
    $members->execute(array(
        $user_id,
    ));
    $member = $members->fetch();

//    ##他のユーザーが再生できないようにする｡
//    $change_sound = $dbh->prepare('update  hoot_sound set listen = 1 where id = ?');
//    $change_sound->execute(array(
//        $hoot_sound_id,
//    ));

} else {
    header('Location: signin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Page</title>
    <link rel="stylesheet" href="./css/reboot.min.css" />
    <link rel="stylesheet" href="./css/detail.css" />
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo" />
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="#" class="header__signout">ログアウト</a> -->
    </header>
    <div class="main-container">
        <div class="user-icon">
            <img src="./icon/bubble_sos.svg" alt="bubble image" class="bubble">
            <img src="./icon/<?php print($member['picture']);?>_sitting.svg" alt="owl image" class="sitting-owl">
        </div>
        <div class="details">
            <audio src="recup/data/<?php print($filename);?>" controlslist="nodownload" controls></audio>
        </div>
    </div>
    <button class="back-btn" onclick="location.href='index.php'">
        <img src="./icon/arrow.png" alt="arrow image" />
    </button>
</div>
</body>
</html>
