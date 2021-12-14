<?php
require '../dbconnect.php';

session_start();

if (isset($_SESSION['join'])) $data = $_SESSION['join'];

if (!empty($_POST)) {
    $data = check($_POST);
    if ($data['name'] == '') $errors['name'] = 'ユーザ名を入力してください。';
    if ($data['email'] == '') $errors['email'] = 'Emailを入力してください。';
    if ($data['password'] == '') $errors['password'] = 'パスワードを入力してください。';
    if (strlen($data['password']) < 4) $errors['password'] = 'パスワードは4文字以上で入力してください。';

    //Email 重複チェック
    if (empty($errors)) {
        $sql = 'SELECT COUNT(*) AS cnt FROM members WHERE email = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$data['email']]);
        $record = $stmt->fetch();
        if ($record['cnt'] > 0) {
            $errors['email'] = 'Emailは既に登録されています';
        }
    }

    if (empty($errors)) {
        //アップロードしたファイル名を決める
        $image = date('YmdHis') . $_FILES['image']['name'];
        //１つ上の member_pictrure フォルダに画像を移動する
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

        $_SESSION['join'] = $data;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit;
    }
}

function check($posts)
{
    if (empty($posts)) return;
    foreach ($posts as $column => $value) {
        //サニタイズ
        $posts[$column] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $posts;
}
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
            <div class="col-8">
                <h2 class="h2 text-center p-3">会員登録</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" value="<?= @$data['name'] ?>">
                        <label for="">ニックネーム</label>
                        <p class="text-danger"><?= @$errors['name'] ?></p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" value="<?= @$data['email'] ?>">
                        <label for="">メールアドレス</label>
                        <p class="text-danger"><?= @$errors['email'] ?></p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password">
                        <label for="">パスワード</label>
                        <p class="text-danger"><?= @$errors['password'] ?></p>
                    </div>

                    <h3 class="h3">プロフィール画像</h3>
                    <input type="file" name="image" size="35">

                    <div class="text-center">
                        <button class="btn btn-primary">確認</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>