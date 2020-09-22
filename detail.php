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

    ##他のユーザーが再生できないようにする｡
    $change_sound = $dbh->prepare('update  hoot_sound set listen = 1 where id = ?');
    $change_sound->execute(array(
        $hoot_sound_id,
    ));

} else {
    header('Location: signin.php');
    exit();
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <img src="./icon/<?php print($member['picture']);?>" alt="">
    <p><?php print($member['name']);?></p>
    <audio src="recup/data/<?php print($filename);?>" controls></audio>
    <div>
        #<?php print($hashtag_data['generation']);?>代
        #<?php print($hashtag_data['gender']);?>
        <?php print($hashtag_data['freeword']);?>
    </div>
    <a href="index.php">戻る</a>
</body>
</html>
