<?php

include '_base.php';

require 'vendor/autoload.php';

use Zxing\QrReader;

$p->title = 'Scan Table QR';

if ($p->post) {
    $image = $p->req('image');
    
    // create qr reader with input image
    $qr = new QrReader($image);

    // decoding qr to text
    $url = $qr->text();

    // cut HTTP BASE
    $url = substr($url, -21);
    //var_dump($url);

    if ($url) $p->redirect($url);
    $p->redirect('scan-again.php');
} 
// any fail return to home


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $p->title ?></title>
    <link rel="shortcut icon" href="/image/favicon.png">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.validate.js"></script>
    <script src="/js/additional-methods.js"></script>
    <script src="/js/webcam.min.js"></script>
    <script src="/js/app.js"></script>
    <?= $p->head ?>
</head>
<body>
    <header>
        <h1 class="header"><a href="/home.php">DT Restaurant</a></h1>
    </header>

    <h1>Please rescan the Table QR Code</h1>
    <h1>Table reference not found</h1>
  
<div class="container">
    <h2>Scan Table Qr Code</h2>
    <form method="POST">
        <div>
            <div>
                <div id="my_camera"></div>
                <br/>
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div>
                <br/>
                <button onClick="take_snapshot()" class="btn btn-success">Click here to Scan</button>
            </div>
        </div>
    </form>
</div>
   
<!-- Configure a few settings and open device camera -->
<script language="JavaScript">
    Webcam.set({
        width: 490,
        height: 390,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
  
    Webcam.attach( '#my_camera' );
  
    // take snapshot using camera
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
        } );
    }
</script>
 
<?php include '_footer.php';