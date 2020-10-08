<?php
include '../_base.php';
require '../vendor/autoload.php';

use Zxing\QrReader;

if ($p->post) {
    $image = $p->req('image');
    if (!$image) $p->redirect('../home.php'); //redirect if not exist
    
    // create qr reader with input image
    $qr = new QrReader($image);

    // decoding qr to text
    $prm = $qr->text();

    // check if scanned item is exist
    // input ?id=XXXX  (trim to XXXX for checking)
    $check = substr($prm, 4);

    $stmt = $db->prepare('SELECT id FROM item WHERE id = ?');
    $stmt->execute([$check]);
    $item = $stmt->fetch();

    // create url (../order/order.php?id=XXXX)
    $url = '../order/order.php' . $prm;
    //var_dump($url);

    // if item exist redirect to ordering
    if ($item)
        $p->redirect($url);
} 
// any fail return to home
$p->redirect('../home.php');
