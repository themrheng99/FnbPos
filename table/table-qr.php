<?php
include '../_base.php';
include '../lib/phpqrcode/qrlib.php';

$p->auth('admin, staff');

$id = $p->req('id');
$stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
$stmt->execute([$id]);
$t = $stmt->fetch();
if (!$t) $p->redirect('../home.php');

// generate title
$i = substr($t->id, 1);
$p->title = 'QR for ' . $TABLETYPE[$t->type] . ' ' . $i;

?>
<script src="/js/jquery.js"></script>
<script src="/js/app.js"></script>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<style>
h1 {
    margin: 0;
    padding: 20;
    font: 24pt 'calibri';
    text-align: center;
}

.c {
    margin: auto;
    width: 30%;
}

img {
    margin: auto;
    width: 100%;
}

.mid-btn {
    margin: auto;
    width: 100%;
}

</style>

<div class="container c">
    <title><?= $p->title ?></title>
    <h1><?= $p->title ?></h1>
    <?php
    $dir = '../table-qr';
    
    $qrcontent = $BASE_URL . "/signin.php?table=$t->id";

    // generating
    QRcode::png($qrcontent, $dir.'/'.$t->id.'.png', QR_ECLEVEL_L, 10);
        
    // display qr and download button
    echo "
        <img src='../table-qr/$t->id.png'>
        <br></br>
        <button data-download='../$dir/$t->id.png' class='btn btn-info mid-btn'>Download</button>
    ";
    ?>
    
</div>