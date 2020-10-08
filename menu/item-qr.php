<?php
include '../_base.php';
include '../lib/phpqrcode/qrlib.php';

$p->auth('admin, staff');

// to check if generating Single qr code || All qr code
$fn = $p->get('fn') ? $p->get('fn') : '';
$dir = '../item-qr';

echo "
    <link rel='stylesheet' href='../css/bootstrap.min.css'>
    <link rel='stylesheet' href='../css/app.css'>
";

// for single qr
if ($fn == 'single') {
    
    $id = $p->get('id') ? $p->get('id') : '';

    if (!$id) $p->redirect('menu-manage.php');

    $stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
    $stmt->execute([$id]);
    $i = $stmt->fetch();

    if (!$i) $p->redirect('menu-manage.php');
    
    // content that will encode as Qrcode
    $qrcontent = "?id=$i->id";

    // generating qr code
    // save qr into folder
    QRcode::png($qrcontent, $dir.'/'.$i->id.'.png', QR_ECLEVEL_L, 3);

    // display
    $str = "
        <div class='container'>
            <div class='menu-table'>
                <div class='row'>
                    <div class='head'><p>$i->id</p></div>
                </div>
                <div class='row'>
                    <div class='body'>
                        <image src='../menu-image/$i->image'>
                        <p><b>$i->name</b></p>
                        <p>RM <b>$i->price</b></p>
                        <image src='../item-qr/$i->id.png'>
                    </div>
                </div>
            </div>
        </div>
    ";
    echo $str;

    //all qr
} elseif ($fn == 'all') {
    $stmt = $db->query('SELECT * FROM item');
    $items = $stmt->fetchAll();
    $count = 0;

    $str = "<div class='container'>";
    foreach(array_keys($CATEGORIES) as $c) {
        $str .= "<div>";
        foreach ($items as $i) {
            if ($i->category === $c) {  
                $qrcontent = "?id=$i->id";
                
                // generating
                QRcode::png($qrcontent, $dir.'/'.$i->id.'.png', QR_ECLEVEL_L, 3);
                
                $str .= "
                    <div class='menu-table'>
                        <div class='row'>
                            <div class='head'><p>$i->id</p></div>
                        </div>
                        <div class='row'>
                            <div class='body'>
                                <image src='../menu-image/$i->image'>
                                <p><b>$i->name</b></p>
                                <p>RM <b>$i->price</b></p>
                                <image src='../item-qr/$i->id.png'>
                            </div>
                        </div>
                    </div>
                ";
            }
        }

        $str .= "</div>";
        //$str .= "<br></br>";
    }
    
    $str .= "</div>";
    echo $str;
} else $p->redirect('menu-manage.php');

?>