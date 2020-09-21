<?php
session_start();
require('./pdo_connect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $dbh->prepare('SELECT * FROM hoot_user WHERE id=?');
    $sql = 'SELECT * FROM hoot_sound WHERE listen=0';
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
      <div class="owl">
        <ul class="owl__list">
            <?php foreach($dbh->query($sql) as $row):?>
          <li class="owl__item">
            <a href="detail.php?hoot_sound_id=<?php print($row['id']);?>&user_id=<?php print($row['user_id']);?>">
              <img src="./icon/<?php
              $members->execute(array(
                  $row['user_id'],
              ));
              $member = $members->fetch();
              print($member['picture']);
              ?>" alt="owl image">
            </a>
          </li>
            <?php endforeach;?>
        </ul>
      </div>
    </div>
    <button class="menu-left" onclick="location.href='mypage.php'">
      <img class="home" src="./icon/home.png" alt="home img">
    </button>
    <!-- <button class="menu-left" onclick="location.href='index.php'">
      <img class="timeline" src="./icon/timeline.png" alt="timeline img">
    </button> -->
    <button class="menu-right" onclick="location.href='record.php'">
      <img class="mic" src="./icon/mic.png" alt="mic img">
    </button>
  </div>
</body>
</html>