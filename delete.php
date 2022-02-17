<?php
/**
 * 削除処理
 * 
 */
session_start();
require_once 'dbconnect.php';

//認証処理は auth.php で共通化
require_once 'auth.php';

//共通で利用する関数ファイル helpers.php 
require_once 'helpers.php';

//返信1件表示
if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
    $sql = 'DELETE FROM posts WHERE id = ?';
    $stmt = $db->prepare($sql);
    $results = $stmt->execute([$id]);
}

header('Location: index.php');
exit;