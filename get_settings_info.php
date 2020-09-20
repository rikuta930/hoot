<?php
$appEnv = getenv('APP_ENV');
if ($appEnv == 'develop')
{
    $settings = require __DIR__ . '/config/settings_develop.php';
}elseif ($appEnv == 'production')
{
    $settings = require __DIR__. '/config/settings_production.php';
}else{
    print('Maybe some Error occured;');
}

$user = $settings['settings']['db']['user'];
$dbname = $settings['settings']['db']['dbname'];
$password =  $settings['settings']['db']['password'];
$host = $settings['settings']['db']['host'];
?>