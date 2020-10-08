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

// remove order which match selected
$stmt = $db->prepare('DELETE FROM `order_item` WHERE item = ? AND `order` = ? AND id = ?');
$stmt->execute([$item, $order, $oid]);
$p->redirect($_SERVER['HTTP_REFERER']);