<?php
include '../_base.php';

$p->auth('customer, admin, staff');

if ($p->post) {
    $item = $p->req('item') ;
    $qty = $p->req('quantity') ;
    $remark = $p->req('remark') ;

    $stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
    $stmt->execute([$item]);
    $i = $stmt->fetch();
    if (!$i) $p->redirect('../home.php');
    if ($qty == null) $p->redirect('../home.php');

    // input quantity start with 0
    // therefore plus 1  (input -> 0, actual -> 1)
    $qty++;

    if ($p->role == 'customer') {
        $order = $p->order;
    } else {
        // get input table, check if order is exist
        $table = $p->req('table');
        $stmt = $db->prepare('SELECT MAX(id) FROM `order` WHERE `table` = ? AND status = ?');
        $stmt->execute([$table, 0]);
        $order = $stmt->fetchColumn();
        if (!$order) $p->redirect('../home.php');
    }

    // count subtotal
    $subtotal = $i->price * $qty;

    // insert to order_item
    $stmt = $db->prepare('INSERT INTO `order_item` (`order`, item, quantity, remark, subtotal) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$order, $item, $qty, $remark, $subtotal]);
} else $p->redirect('../home.php');

include '../_header.php';
?>

<div class='container'>
    <a href="../menu/menu.php<?php if ($p->role != 'customer') echo "?table=".$table; ?>" class="btn btn-info">Continue to order</a>
    <a href="order-confirmation.php<?php if ($p->role != 'customer') echo "?table=".$table; ?>" class="btn btn-success">Confirm your order(s) now</a>
</div>

<?php include '../_footer.php';