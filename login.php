<?php
require 'dbconnect.php';

session_start();

//ログインボタンでデータ送信されたら
if (!empty($_POST)) {
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $sql = 'SELECT * FROM members WHERE email = ? AND password = ?';
        $login = $db->prepare($sql);
        $login->execute(
            [ $_POST['email'], sha1($_POST['password']) ]
        );
        $member = $login->fetch(PDO::FETCH_ASSOC);
        if ($member) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            header('Location: index.php');
            exit;
        } else {
            $errors['login'] = 'メールアドレスまたはパスワードが間違っています。';
        }
    } else {
        $errors['login'] = 'メールアドレスとパスワードを入力してください。';
    }
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
        <h2 class="h2">ログイン</h2>
        <form action="login.php" method="post">
            <div class="form-floating mb-3">
                <input class="form-control" type="email" name="email">
                <label for="">メールアドレス</label>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="password" name="password">
                <label for="">パスワード</label>
            </div>
            <div>
                <button class="btn btn-primary">ログイン</button>
            </div>
        </form>
    </div>
</body>

</html>