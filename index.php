<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>图片合成</title>
</head>
<body>
<?php
// 获取背景图片
$bg_img = imagecreatefromjpeg("bg.jpg");
// 获取二维码
$qr_name_list = scandir("qr");
// 循环处理
foreach ($qr_name_list as $qr_name) {
    if (in_array($qr_name, array('.', '..'))) {
        continue;
    }
    $qr_img = imagecreatefrompng("qr/" . $qr_name);
    $machine_no = explode('.', $qr_name)[0];
    $qr_name_save = $machine_no . ".jpg";
    $machine_no = explode('_', $machine_no)[1];
    echo $machine_no . "<br>";
    $machine_no = preg_replace('/([\x80-\xff]*)/i', '', $machine_no);
    echo $machine_no . "<br>";
    echo "处理 $machine_no 号机器中<br>";
    $temp_bg_img = $bg_img;
    $image_p = imagecreatetruecolor(860, 860);
    imagecopyresampled($image_p, $qr_img, 0, 0, 0, 0, 860, 860, imagesx($qr_img), imagesy($qr_img));
    echo "放大二维码<br>";
    imagecopy($temp_bg_img, $image_p, 5116, 47, 0, 0, imagesx($image_p), imagesy($image_p));
    echo "图片合成<br>";
    imagefilledrectangle($temp_bg_img, 5433, 366, 5654, 591, 0xFFFFFF);
    echo "二维码中的正方形<br>";
    $font_size = 0;
    $font_length = strlen($machine_no);
    if ($font_length <= 2) {
        $font_size = 120;
    } else {
        $font_size = 80;
    }
    $ftbbox = imageftbbox($font_size, 0, "msyh.ttf", $machine_no);
    $font_width = abs($ftbbox[4] - $ftbbox[0]);
    $font_height = abs($ftbbox[5] - $ftbbox[1]);
    echo $font_width . "_" . $font_height;
    $font_x = round(5534 - $font_width / 2);
    $font_y = round(470 + $font_height / 2);
    imagefttext($temp_bg_img, $font_size, 0, $font_x, $font_y, 0x83b12c, "msyh.ttf", $machine_no);
    echo "机器编号文字<br>";
    // 输出
    imagejpeg($temp_bg_img, 'output/' . $qr_name_save);
    echo $qr_name . " 图片输出<br>";
}
?>
</body>
</html>
