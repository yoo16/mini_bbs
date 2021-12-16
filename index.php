<?php
session_start();
require 'dbconnect.php';

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
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="h1">ひとこと掲示板</h1>
        <div>
            <p><?= $member['name'] ?></p>
        </div>
        <form action="" method="post">
            <dl>
                <dd>
                    <textarea 
                    name="message" 
                    class="form-control" 
                    placeholder="いまどうしてる？"
                    ></textarea>
                </dd>
            </dl>
            <div>
                <button class="btn btn-primary">投稿</button>
            </div>
        </form>
    </div>
</body>

</html>