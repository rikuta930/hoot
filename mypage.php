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

    $sth = $dbh->prepare('SELECT * FROM hoot_sound WHERE user_id=? ORDER BY time DESC');
    $sth->execute(array(
        $_SESSION['id'],
    ));
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);

    $hashtags = $dbh->prepare('SELECT * FROM hoot_hashtag WHERE id=?');

    $delete_table = $dbh->prepare('DELETE FROM hoot_sound WHERE ?::text < CURRENT_TIMESTAMP(0)::text;');
} else {
    header('Location: signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
    <link rel="stylesheet" href="./css/reboot.min.css">
    <link rel="stylesheet" href="./css/mypage.css">
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo">
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <a href="logout.php" class="header__signout">ログアウト</a>
    </header>
    <div class="page-title">
        <h2>マイページ</h2>
        <button onclick="location.href='edit-profile.php'">編集</button>
    </div>
    <div class="user-icon">
        <img src="./icon/<?php echo $member['picture'];?>_sitting.svg" alt="icon img">
    </div>
    <div class="main-container">
        <ul class="list">
            <?php foreach($data as $datum):?>
            <?php
                $timestamp = strtotime('+1 day ' . $datum['time']);
                $time = date("Y-m-d H:i:s", $timestamp);

                $delete_table->execute(array(
                    $time,
                )) ?>
            <li class="list-item">
                <div class="list-item__info">
                    <div class="list-item__upper">
                        <div class="bubble">
                            <img src="./icon/bubble_<?php
                            $hashtags->execute(array(
                                $datum['hoot_hashtag_id'],
                            ));
                            $category = $hashtags->fetch();
                            print($category['category'])
                            ?>.svg" alt="bubble image" class="bubble">
                        </div>
                        <div class="time">1時間前</div>
                    </div>
                    <audio src="./recup/data/<?php echo $datum['name']; ?>" controlslist="nodownload" controls></audio>
                    <div class="list-item__bottom">
                        <?php if ($datum['listen']==0):?>
                        <img src="./icon/ear_not_heard.svg" alt="ear image">
                        <?php else:?>
                        <img src="./icon/ear_heard.svg" alt="ear image">
                        <?php endif;?>
                        <?php
                        $res_data = $dbh->prepare('SELECT * FROM hoot_sound WHERE res_id=?');
                        $res_data->execute(array(
                           $datum['id']
                        ));
                        $res_datum = $res_data->fetch();
                        if ($res_datum):?>
                            <a href="detail.php?hoot_sound_id=<?php print($res_datum['id']);?>&user_id=<?php print($res_datum['user_id']);?>"><img src="./icon/comment_sent.svg" alt=""></a>
                        <?php else :?>
                            <img src="./icon/comment_not_sent.svg" alt="">
                        <?php endif;?>
                        <a href="delete.php?id=<?php echo $datum['id'];?>"><img class="trash" src="./icon/trash.png" alt="trash image"></a>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <button class="menu-left" onclick="location.href='index.php'">
        <img class="timeline" src="./icon/timeline.png" alt="timeline img">
    </button>
    <button class="menu-right" onclick="location.href='record.php'">
        <img class="mic" src="./icon/mic.png" alt="mic img">
    </button>
</div>
</body>
</html>