<?php
include '../_base.php';

$p->auth('admin, staff');

$v = $p->req('v') ?? '1';
$ids = $p->post_notrim('ids');

// check if any input id
if ($ids) {
    $in = str_repeat('?,', count($ids)) . '0';

    // update all order_item selected to update status
    $stmt = $db->prepare("UPDATE `order_item` SET status = $v WHERE id IN ($in)");
    $stmt->execute($ids);
    $count = $stmt->rowCount();
    $p->temp('info', "<b>$count</b> order(s) updated");
    $p->redirect('kitchen.php');
}
$p->redirect('../home.php');