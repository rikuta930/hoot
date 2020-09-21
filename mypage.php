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
    <div class="main-container">
      <div class="profile">
        <div class="profile__left">
          <div class="profile__icon">
            <img src="./icon/<?php print($member['picture']);?>" alt="icon img">
          </div>
          <div class="profile__follow-and-follower">
            <div>
              <span class="label">フォロー</span>
              <span class="number">10</span>
            </div>
            <div>
              <span class="label">フォロワー</span>
              <span class="number">10</span>
            </div>
          </div>
        </div>
        <div class="profile__right">
          <h4 class="profile__name"><?php print($member['name']);?></h4>
          <p class="profile__id"><?php print($member['id']);?></p>
          <p class="profile__introduction">こんにちは。ここには自己紹介が表示されます。よろしくね！</p>
          <button class="profile__btn" onclick="location.href='edit-profile.php'">プロフィール編集</button>
        </div>
      </div>
      <ul class="list">
          <?php foreach ($data as $datum):?>
          <?php
              $hashtags->execute(array(
                  $datum['hoot_hashtag_id'],
              ));
              $hashtag = $hashtags->fetch(PDO::FETCH_ASSOC);
          ?>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/<?php print($member['picture']);?>" alt="icon img">
          </div>
          <div class="list-item__info">
            <h2 class="list-item__name">
            <?php print($member['name']); ?>
            </h2>
            <audio src="./recup/data/<?php echo $datum['name'];?>" controls></audio>
            <span class="list-item__tag">#<?php echo $hashtag['gender']?> </span>
            <span class="list-item__tag"><?php echo $hashtag['freeword'] ?></span>
<!--            <div class="list-item__heard">-->
<!--              <span class="list-item__number">17</span>-->
<!--              <img src="./icon/ear_black.png" alt="ear img">-->
<!--            </div>-->
          </div>
        </li>
          <?php endforeach; ?>
      </ul>
    </div>
    <!-- <button class="menu-left" onclick="location.href='mypage.html'">
      <img class="home" src="./icon/home.png" alt="home img">
    </button> -->
    <button class="menu-left" onclick="location.href='index.php'">
      <img class="timeline" src="./icon/timeline.png" alt="timeline img">
    </button>
    <button class="menu-right" onclick="location.href='record.php'">
      <img class="mic" src="./icon/mic.png" alt="mic img">
    </button>
  </div>
</body>
</html>