<?php
include '../_base.php';
$p->title = 'Order';

$p->auth('customer, admin, staff');

if ($p->role != 'customer') {
    // check table existancy
    $table = $p->get('table') ;
    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$table]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('../home.php');
}

$id = $p->get('id') ;

$stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) $p->redirect('../menu/menu.php');

$quantity = $remark = '';

include '../_header.php';
?>

<a href="../menu/menu.php" class="btn btn-outline-info">Back</a>
<br></br>

<div class="container">
    <div class="order-item">
        <image src="../menu-image/<?= $item->image ?>">
        <p>Id: <b><?= $item->id ?></b></p>
        <p>Name: <b><?= $item->name ?></b></p>
        <p>Description: <b><?= $item->description ?></b></p>
        <p>Price: RM <b><?= $item->price ?></b></p>

        <form action="order-add.php" method="POST" class="order-item-form">
            <?php 
                if ($p->role != 'customer') {
                    echo "<input type='text' name='table' value='" . $table . "'style='visibility: hidden'>";
                }
            ?>
            <input type="text" name="item" value="<?= $item->id ?>" style="visibility: hidden">
            <br></br>
            <p><b>*Per order limit to 5 units (Go menu to add again if you want more than 5).</b></p>
            <?= $h->label('quantity') ?>
            <?= $h->radios('quantity', null, ['1', '2', '3', '4', '5']) ?>
            <?= $h->label('remark') ?>
            <?= $h->text('remark', null, 100, 'data-upper') ?>
            <br></br>
            <input type="submit" value="Make Order" class="btn btn-success">
        </form>
    </div>
</div>


<?= $p->temp('info') ?>
<br></br>
