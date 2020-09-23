<?php
session_start();
require('pdo_connect.php');

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

$statement = $dbh->prepare('INSERT INTO hoot_user(email, password, picture) VALUES(?, ?, ?)');

$statement->execute(array(
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password']),
    'owl_green'
));
unset($_SESSION['join']);

header('Location: signin.php');
exit();


?>