<?php
try {
    $db = new PDO(
        'mysql:dbname=mini_bbs;127.0.0.1;charset=utf8',
        'root',
        ''
    );
} catch (PDOException $e) {
    echo 'DB接続エラー' . $e->getMessage();
}
