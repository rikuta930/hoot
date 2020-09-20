<?php
require 'get_settings_info.php';
//
$dsn = 'pgsql:dbname=' . $dbname . ';host=' . $host;

try{
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e){
    echo '接続失敗: ' . $e->getMessage() . "\n";
    exit();
}
