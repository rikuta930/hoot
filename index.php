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
  <title>Timeline</title>
  <link rel="stylesheet" href="./css/reboot.min.css">
  <link rel="stylesheet" href="./css/index.css">
</head>
<body>
  <div class="global-container">
    <header class="header">
      <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo">
      <img src="./icon/search.png" alt="search img" class="header__search">
      <!-- <a href="#" class="header__signout">ログアウト</a> -->
    </header>
    <div class="main-container">
      <ul class="list">
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_girl.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            Hana
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#女性</span>
            <span class="list-item__tag">#嬉しい</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_black.png" alt="ear img">
            </div>
          </div>
        </li>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_boy.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            Taro
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#男性</span>
            <span class="list-item__tag">#悲しい</span>
            <span class="list-item__tag">#泣</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_purple.png" alt="ear img">
            </div>
          </div>
        </li>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_secret.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            豆腐
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#嬉しい</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_purple.png" alt="ear img">
            </div>
          </div>
        </li>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_girl.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            Hana
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#女性</span>
            <span class="list-item__tag">#嬉しい</span>
            <span class="list-item__tag">#嬉しい</span>
            <span class="list-item__tag">#嬉しい</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_black.png" alt="ear img">
            </div>
          </div>
        </li>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_secret.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            豆腐
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#嬉しい</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_black.png" alt="ear img">
            </div>
          </div>
        </li>
        <li class="list-item">
          <div class="list-item__icon">
            <img src="./icon/icon_others.png" alt="icon img">
          </div>
          <div class="list-item__info">
            <h4 class="list-item__name">
            おでん
            </h4>
            <audio src="#" controls></audio>
            <span class="list-item__tag">#楽しい</span>
            <span class="list-item__tag">#最高</span>
            <span class="list-item__tag">#20代</span>
            <div class="list-item__heard">
              <span class="list-item__number">17</span>
              <img src="./icon/ear_purple.png" alt="ear img">
            </div>
          </div>
        </li>
      </ul>
    </div>
    <button class="menu-left" onclick="location.href='mypage.php'">
      <img class="home" src="./icon/home.png" alt="home img">
    </button>
    <!-- <button class="menu-left" onclick="location.href='index.html'">
      <img class="timeline" src="./icon/timeline.png" alt="timeline img">
    </button> -->
    <button class="menu-right" onclick="location.href='record.php'">
      <img class="mic" src="./icon/mic.png" alt="mic img">
    </button>
  </div>
</body>
</html>