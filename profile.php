<?php
session_start();
require 'dbconnect.php';

//認証処理は auth.php で共通化
require_once 'auth.php';

//共通で利用する関数ファイル helpers.php 
require_once 'helpers.php';

//時間帯を日本時刻にする
date_default_timezone_set('Asia/Tokyo');

$sql = 'SELECT members.name, members.picture, posts.* 
    FROM members, posts 
    WHERE members.id = posts.member_id
        AND posts.member_id = ?
    ORDER BY posts.created DESC';
$stmt = $db->prepare($sql);
$stmt->execute([$member['id']]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="profile">
                <div>
                    <img src="<?= memberPicture($member['picture']) ?>" />
                </div>
                <div class="member-name">
                    <?= $member['name'] ?>
                </div>
                <div>
                    <?= date('Y年m月d日', strtotime($member['created'])) ?>から掲示板を利用しています。
                </div>
            </div>
            <div class="border-bottom border-top-0 border-end-0 border-start-0">
                <div class="tweet-body">
                    <?php if ($posts) : ?>
                        <?php foreach ($posts as $post) : ?>
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
                                        <a href="view.php?id=<?= $post['id'] ?>" class="text-dark"><?= $post['message'] ?></a>
                                    </div>
                                    <div class="tweet-menu mb-3">
                                        <a href="index.php?res_id=<?= $post['id'] ?>"><span class="icon icon-bubble"></span></a>
                                        <span class="icon icon-loop"></span>
                                        <span class="icon icon-heart"></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>