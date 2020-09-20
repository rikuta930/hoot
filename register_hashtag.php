<?php
session_start();
require('./pdo_connect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
    $sth = $dbh->prepare('SELECT * FROM hoot_sound WHERE user_id=? AND hoot_hashtag_id is null');
    $sth->execute(array(
        $_SESSION['id'],
    ));
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($_POST)){
        $hashtag = $dbh->prepare('INSERT INTO hoot_hashtag VALUES(?,?,?)');

        $hashtag->execute(array(
            $_SESSION['register_hashtag']['generation'],
            $_SESSION['register_hashtag']['gender'],
            $_SESSION['register_hashtag']['freeword'],
        ));

        $sound = $dbh->prepare('UPDATE hoot_sound SET hoot_hashtag_id = ? WHERE user_id=? AND hoot_hashtag_id is null;');

        $sound->execute(array(
            $_SESSION['register_hashtag']['generation'],
            $_SESSION['register_hashtag']['gender'],
            $_SESSION['register_hashtag']['freeword'],
        ));



        unset($_SESSION['join']);

        header('Location: thanks.php');
        exit();
    }
} else {
    header('Location: signin.php');
    exit();
}
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <h1>確認画面</h1>
    <?php echo $_SESSION['register_hashtag']['generation']?>代<br><br>
    　　　<?php foreach ($data as $row): ?>
        <audio src="./recup/data/<?php echo $row['name']; ?>" controls></audio><br><br>
        　　　<?php endforeach; ?>
    <?php echo $_SESSION['register_hashtag']['gender']?><br>
    <?php echo $_SESSION['register_hashtag']['freeword']?><br>
    <input type="submit" value="登録する!">
</form>
</body>
</html>
