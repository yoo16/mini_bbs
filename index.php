<?php
session_start();
require_once 'dbconnect.php';

//認証処理は auth.php で共通化
require_once 'auth.php';

//共通で利用する関数ファイル helpers.php 
require_once 'helpers.php';

//時間帯を日本時刻にする
date_default_timezone_set('Asia/Tokyo');

//返信1件表示
$message = '';
$res_id = 0;
if (isset($_REQUEST['res_id'])) {
    $res_id = htmlspecialchars($_REQUEST['res_id']);
    $sql = 'SELECT members.name, members.picture, posts.* 
    FROM members, posts 
    WHERE members.id = posts.member_id
        AND posts.id = ?
    ORDER BY posts.created DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute([$res_id]);
    $response = $stmt->fetch(PDO::FETCH_ASSOC);
    $message = "@{$response['name']} {$response['message']}";
}

//投稿
if (!empty($_POST) && $_POST['message'] != '') {
    $sql = 'INSERT INTO posts 
            SET member_id = ?, message = ?, reply_post_id = ?, created = NOW();';
    $message = $db->prepare($sql);
    $message->execute([
        $_SESSION['id'],
        $_POST['message'],
        $res_id,
    ]);
    header('Location: index.php');
    exit;
}

//投稿一覧
$sql = 'SELECT members.name, members.picture, posts.* 
        FROM members, posts 
        WHERE members.id = posts.member_id
        ORDER BY posts.created DESC';
$posts = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/default.css">
</head>

<body>
    <div class="row">
        <div class="col-3">
            <?php include('components/side.view.php') ?>
        </div>

        <div class="col-9">
            <h2 class="h2">最新ツイート</h2>
            <form action="" method="post">
                <div class="tweet-post">
                    <textarea name="message" class="form-control" placeholder="いまどうしてる？"><?= $message ?></textarea>
                    <input type="hidden" name="reply_post_id" value="<?= $res_id ?>">
                </div>
                <div class="d-grid gap-2 col-3 mx-auto">
                    <button class="btn btn-primary btn-rounded" data-mdb-ripple-color="dark">投稿</button>
                </div>
            </form>
            <?php foreach ($posts as $post) : ?>
                <div class="border-bottom border-top-0 border-end-0 border-start-0">
                    <div class="tweet-body">
                        <div class="tweet-img">
                            <img src="<?= memberPicture($post['picture']) ?>" />
                        </div>
                        <div class="tweet">
                            <div>
                                <span class="fw-bold"><?= $post['name'] ?></span>
                                <span class="ms-1 text-secondary">
                                    <?= postTime($post['created']) ?>
                                </span>
                            </div>
                            <div class="tweet-text mt-2 mb-2">
                                <a href="view.php?id=<?= $post['id'] ?>" class="text-dark">
                                    <?= nl2br($post['message']) ?>
                                </a>
                            </div>
                            <div class="tweet-menu mb-3">
                                <a href="index.php?res_id=<?= $post['id'] ?>"><span class="icon icon-bubble"></span></a>
                                <span class="icon icon-loop"></span>
                                <span class="icon icon-heart"></span>
                                <?php if ($post['member_id'] == $member['id']) : ?>
                                    <form id="form-delete" action="delete.php" method="post">
                                        <a href="#" id="delete-btn" onclick="deletePost()"><span class="icon icon-trash"></span></a>
                                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                    </form>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/default.js"></script>
</body>

</html>