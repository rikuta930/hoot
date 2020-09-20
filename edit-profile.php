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
            <img src="./icon/icon_girl.png" alt="icon image">
          </div>
          <div class="profile__mail-and-id">
            <span class="profile__mail">
              aaaaaa@sample.com
            </span>
            <span class="profile__id">
              aaaaaa
            </span>
          </div>
        </div>
        <form class="form">
          <label for="name" class="form__title">ユーザー名</label>
            <input id="name" type="text" class="form__info"><br>
          <label for="gender" class="form__title">性別</label>
            <select name="gender" class="form__info">
              <option value=""></option>
              <option value="boy">男性</option>
              <option value="girl">女性</option>
              <option value="others">その他</option>
              <option value="secret">無回答</option>
            </select><br>
          <label for="introduction" class="form__title">自己紹介</label>
          <textarea name="introduction" class="form__info"></textarea>
          <div class="form__btn-wrapper">
            <button class="form__btn">変更</button>
          </div__btn-wrapper>
        </form>
      </div>
      <button class="back-btn" onclick="history.back()">
        <img src="./icon/arrow.png" alt="arrow image">
      </button>
    </div>
  </body>
</html>
