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
    var_dump($_POST);

    if (!empty($_POST)) {
        $sth = $dbh->prepare('UPDATE hoot_user SET name = ?, gender =? WHERE id=?');
        $sth->execute(array(
                $_POST['name'],
                $_POST['gender'],
                $_SESSION['id'],
        ));
        }else {
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit profile</title>
    <link rel="stylesheet" href="./css/reboot.min.css" />
    <link rel="stylesheet" href="./css/edit-profile.css" />
  </head>
  <body>
    <div class="global-container">
      <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo" />
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="signin.html" class="header__signout">ログアウト</a> -->
      </header>
      <div class="main-container">
        <h2 class="page-title">プロフィール編集</h2>
        <div class="profile__info">
          <div class="profile__icon">
            <img src="./icon/<?php print($member['picture']);?>" alt="icon image">
          </div>
          <div class="profile__mail-and-id">
            <span class="profile__mail">
              <?php print($member['email']);?>
            </span>
            <span class="profile__id">
              id:<?php print($member['id']);?>
            </span>
          </div>
        </div>
        <form class="form" action="edit-profile.php" method="post">
          <label for="name" class="form__title">ユーザー名</label>
            <input id="name" type="text" name="name" class="form__info" placeholder="<?php print($member['name']);?>" required><br>
          <label for="gender" class="form__title">性別</label>
            <select name="gender" class="form__info" required>
              <option value=""></option>
              <option value="boy">男性</option>
              <option value="girl">女性</option>
              <option value="others">その他</option>
              <option value="secret">無回答</option>
            </select><br>
<!--          <label for="introduction" class="form__title">自己紹介</label>-->
<!--          <textarea name="introduction" class="form__info"></textarea>-->
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
