<?php
session_start();

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    session_destroy();
    header('Location: signin.php');
    exit();
} else {
    header('Location: signin.php');
    exit();
}
?>