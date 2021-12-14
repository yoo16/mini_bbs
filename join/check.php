<?php
require '../dbconnect.php';

session_start();

if (!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit;
}

//check.php の登録ボタンで送信されたら
if (!empty($_POST)) {
    $sql = 'INSERT INTO members SET name = ?, email = ?, 
                        password = ?, picture = ?, created = NOW()';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $_SESSION['join']['name'],
            $_SESSION['join']['email'],
            sha1($_SESSION['join']['password']),
            $_SESSION['join']['image'],
        ]
    );
    unset($_SESSION['join']);
    header('Location: thanks.php');
    exit;
}


$data = $_SESSION['join'];
$image_path = "../member_picture/" . $_SESSION['join']['image'];
$has_file = file_exists($image_path);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
                <h2 class="h2 text-center p-3">会員登録</h2>
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit">
                    <label class="form-label fw-bold" for="">氏名</label>
                    <p><?= $data['name'] ?></p>

                    <label class="form-label fw-bold" for="">メールアドレス</label>
                    <p><?= $data['email'] ?></p>

                    <?php if ($has_file): ?>
                    <img src="../member_picture/<?= $_SESSION['join']['image'] ?>" width="100">
                    <?php endif ?>

                    <div class="text-center p-3">
                        <p>この内容で登録しますか？</p>
                        <button class="btn btn-primary">登録</button>
                        <a href="index.php" class="btn btn-outline-primary">戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>