<?php
session_start();
require '../pdo_connect.php';

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  #音声データをフォルダにアップロード
  $filename = "./data/" . $_POST['fname'] . '.wav';
  $result=@move_uploaded_file($_FILES["sound"]["tmp_name"], $filename);

  #データベースに登録
  $listen = 0;
  $file = $_POST['fname']. '.wav';
  $time = date('Y-m-d H:i:s');
  $sound = $dbh->prepare('INSERT INTO hoot_sound(name, time, user_id,listen) VALUES(?, ?, ?, ?)');
  $sound->execute(array(
      $file,
      $time,
      $_SESSION['id'],
      $listen,
  ));
} else {
  header('Location: signin.php');
  exit();
}
?>
