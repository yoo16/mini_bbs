<?php
function memberPicture($image_name)
{
    $path = "member_picture/{$image_name}";
    if (file_exists($path)) {
        return $path;
    } else {
        return 'images/tamago.png';
    }
}

function postTime($date)
{
    $time = 0;
    $diff = time() - strtotime($date);
    $diff = floor($diff);
    if ($diff < 60) {
        $time = $diff . '秒前';
    } else if ($diff < 60 * 60) {
        $time = floor($diff / 60) . '分前';
    } else if ($diff < 60 * 60 * 24) {
        $time = floor($diff / 60 / 60) . '時間前';
    } else {
        $time = date('Y/m/d H:i', strtotime($date));
    }
    return $time;
}