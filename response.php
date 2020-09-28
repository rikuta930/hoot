<?php
session_start();
require 'pdo_connect.php';
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $dbh->prepare('SELECT * FROM hoot_user WHERE id=?');
    $members->execute(array(
        $_SESSION['id']
    ));
    $member = $members->fetch();

    if (!empty($_POST)) {
        if (empty($error)) {

            $hashtag_id = "";
            for ($i = 0; $i < 30; $i++) {
                $hashtag_id .= mt_rand(0, 9);
            }

            $hashtag = $dbh->prepare('INSERT INTO hoot_hashtag (id, category) VALUES(?,?)');
            $hashtag->execute(array(
                $hashtag_id,
                $_POST['category'],
            ));

            $sound = $dbh->prepare('UPDATE hoot_sound SET res = ?, res_id = ?, hoot_hashtag_id = ? WHERE user_id=? AND hoot_hashtag_id is null;');
            $sound->execute(array(
                $_POST['res'],
                $_POST['res_id'],
                $hashtag_id,
                $member['id'],
            ));

            header('Location: index.php');
            exit();
        }
    }else {
        $error['empty'] = 'empty';
    }
}else {
    header('Location: signin.php');
    exit();
}
?>
