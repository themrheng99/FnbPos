<?php
    // session_start();
    // $change_time = md5(microtime());
    // $get_value = substr($change_time, 0, 6);
    // //rgb
    // $create_image = imagecreate(80, 22);
    // imagecolorallocate($create_image, 51, 112, 183);

    // $text_color = imagecolorallocate($create_image,255,255,255);
    // imageString($create_image,5,15,5,$get_value,$text_color);
    // //output
    // header("Content-type:image/ipeg");
    // imagejpeg($create_image);
    // imagedestroy($create_image);

    session_start();
    $change_time = md5(microtime());
    $_SESSION['get_captcha_value'] = substr($change_time, 0, 6);
    //rgb
    $create_image = imagecreate(80, 22);
    imagecolorallocate($create_image, 51, 112, 183);

    $text_color = imagecolorallocate($create_image,255,255,255);
    imageString($create_image,5,15,5,$_SESSION['get_captcha_value'],$text_color);
    //output
    header("Content-type:image/ipeg");
    imagejpeg($create_image);
    imagedestroy($create_image);
?>