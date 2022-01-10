<?php
//メンバーのid があって、アクアセス時間が1時間以内だったらOK
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //アクセス時間を更新
    $_SESSION['time'] = time();
    // idからユーザ情報を取得
    $sql = 'SELECT * FROM members WHERE id = ?';
    $members = $db->prepare($sql);
    $members->execute([$_SESSION['id']]);
    $member = $members->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: login.php');
    exit;
}