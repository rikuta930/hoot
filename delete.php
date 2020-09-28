<?php
session_start();
require('./pdo_connect.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $data = $dbh->prepare('SELECT * FROM hoot_sound WHERE id=?');
    $data->execute(array(
        $_GET['id'],
    ));
    $datum = $data->fetch();

    if($datum['user_id'] === $_SESSION['id']) {
        $delete_data = $dbh->prepare('DELETE FROM hoot_sound WHERE id=?');
        $delete_data->execute(array(
           $_GET['id'],
        ));
        header('Location: mypage.php');
        exit();
    }else{
        header('Location: mypage.php');
    }

} else {
    header('Location: signin.php');
    exit();
}
?>