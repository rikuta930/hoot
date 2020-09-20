#README

##このコードの趣旨
このコードは､皆さんがゼミサーバーのパスワードやユーザーの個人情報を流出させることを防ぎます｡

##説明
実は､Git の管理化に大切な個人情報(ユーザーの写真等)や､ゼミサーバーのパスワードを入れるのは非常に危険です｡
なぜなら､Github にプッシュした時に､大切な個人情報やゼミのパスワードも一緒にプッシュされてしまうからです｡
夏合宿ではそのようなトラブルを防ぐために先生が作成したコードを使用しました｡
しかし､今後､みなさんがコードを書く上で､既に機能が搭載されているコードではなく､1 から自分で作りたいことがあると思います｡
そのために､こちらのコードを用意しました｡

#開発前の初期設定
いくつかのファイルがはじめから用意されています｡
例えば､pdo_connect.php を使用すれば､データベースにアクセスし､テーブルにデータを追加したり､削除することが簡単にできます｡
.htaccess ではローカル開発環境を自分で構築した人のための記述があり､たった一行をコメントアウトするだけでゼミサーバーの環境とローカル開発環境を使い分ける事ができます｡

これらの機能を有効にするために､settings_develop.php と settings_production.php を開き､主に以下の部分を自分のものに変更してください｡

'dbname' => 'dbname',
'user' => 'dbname',
'password' => 'db_password'

例えば､hoge さんであれば､以下のように編集すれば OK です｡

'dbname' => 'hoge',
'user' => 'hoge',
'password' => '自分のパスワード'

これで初期設定は終了です｡

##データベースの使用方法
```
<?php
require 'pdo_connect.php'

/* 値の配列を渡してプリペアドステートメントを実行する */
$sql = 'SELECT name, colour, calories
    FROM fruits
    WHERE calories < :calories AND colour = :colour';
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':calories' => 150, ':colour' => 'red'));
$red = $sth->fetchAll();
```

大体､すべてのテーブルへの書き出しと取り出しはこの数行を少し変えれば再現できるはずです｡
では､実際に試してみましょう｡

$sql変数にsql文を入れます｡ 
今回は新しいテーブルを作成してから､コードを編集して呼び出してみましょう｡

シェル(ターミナル)を開いて､
ゼミサーバーにssh接続し､接続できたら､データベースに接続します｡
その中で以下のSQL文を実行してください｡

```
create table mybook (id serial primary key, name text, link text);
```

実行できたら､早速､これにデータを入れて呼び出しましょう｡

index.phpの一行目から追記します｡
```
require 'pdo_connect.php'
$sth = $dbh->prepare('INSERT INTO mybook(name, link) VALUES(?, ?)');
$sht->execute(array('リーダブルコード', 'https://www.oreilly.co.jp/books/9784873115658/'));
```

これだけでphpからsql文を実行できます｡
select文で取り出してみましょう
```
$sth = $dbh->prepare('select name, link from mybook where name = ? AND link = ?');
$sth->execute(array('リーダブルコード', 'https://www.oreilly.co.jp/books/9784873115658/'))
$book1 = $sth->fetchAll();
var_dump($book1)
```


更に詳しいことが知りたい方はこちらを御覧ください｡
https://www.php.net/manual/ja/pdo.prepare.php

説明は以上です｡