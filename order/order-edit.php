<?php
include '../_base.php';

$p->auth('customer, admin, staff');

if ($p->role != 'customer') {
    // check if table is exist
    $table = $p->req('table') ;
    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$table]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('../home.php');

    // get lastest order ref, of the table
    $stmt = $db->prepare('SELECT MAX(id) FROM `order` WHERE `table` = ? AND status = ?');
    $stmt->execute([$table, 0]);
    $order = $stmt->fetchColumn();
    if (!$order) $p->redirect('../home.php');
} else {
    // get order ref. from session
    $order = $p->order;
}

// check if given item is exist
$item = $p->req('item') ;
$stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
$stmt->execute([$item]);
$i = $stmt->fetch();
if (!$i) $p->redirect('../home.php');

// check if given order is exist
$oid = $p->req('oid') ;
$stmt = $db->prepare('SELECT * FROM `order_item` WHERE id = ?');
$stmt->execute([$oid]);
$oi = $stmt->fetch();
if (!$oid) $p->redirect('../home.php');

$quantity = $remark = '';

// remove order which match selected
if ($p->post) {
    $quantity = $p->req('quantity');
    $quantity++;
    $remark = $p->req('remark');
    $subtotal = $quantity * $i->price;
    $stmt = $db->prepare('UPDATE `order_item` SET quantity = ?, remark = ?, subtotal = ? WHERE item = ? AND `order` = ? AND id = ?');
    $stmt->execute([$quantity, $remark, $subtotal, $item, $order, $oid]);
    $p->redirect('../menu/menu.php');
}

include '../_header.php';
?>

<a href="../menu.php" class="btn btn-outline-info">Back</a>
<br></br>

<div class="container">
    <div class="order-item">
        <image src="../menu-image/<?= $i->image ?>">
        <p>Id: <b><?= $i->id ?></b></p>
        <p>Name: <b><?= $i->name ?></b></p>
        <p>Description: <b><?= $i->description ?></b></p>
        <p>Price: RM <b><?= $i->price ?></b></p>

        <form method="POST" class="order-item-form">
            <?php 
                if ($p->role != 'customer') {
                    echo "<input type='text' name='table' value='" . $table . "'style='visibility: hidden'>";
                }
            ?>
            <input type="text" name="item" value="<?= $i->id ?>" style="visibility: hidden">
            <br></br>
            <p><b>*Per order limit to 5 units (Go menu to add again if you want more than 5).</b></p>
            <?= $h->label('quantity') ?>
            <?= $h->radios('quantity', null, ['1', '2', '3', '4', '5']) ?>
            <?= $h->label('remark') ?>
            <?= $h->text('remark', null, 100, 'data-upper') ?>
            <br></br>
            <input type="submit" value="Done Edit" class="btn btn-success">
        </form>
    </div>
</div>

<?php include '../_footer.php';