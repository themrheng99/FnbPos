<?php

include '../_base.php';

$p->auth('admin, staff');

if ($p->post) {
    $orderId = $_SESSION['order-reference'] ?? null;
    // ensure order id is exist and not being changed.
    if (!$orderId) {
        $p->temp('info', "Fail to made payment.");
        $p->redirect('../home.php');
    } 

    $grandtotal = $_SESSION['grandtotal'] ?? null;
    $cash = $p->req('cash') ;
    
    $change = $cash - $grandtotal;
    $datetime = date('Y-m-d H:i:s');

    // get staff id to refer as who handle the bill
    $stmt = $db->prepare('SELECT id FROM `staff` WHERE username = ?');
    $stmt->execute([$p->user]);
    $sid = $stmt->fetchColumn();
    
    // update order
    $stmt = $db->prepare('UPDATE `order` SET staff = ?, totalamount = ?, pay= ?, `change` = ?, datetime = ?, status = 1, payment = ? WHERE id = ?');
    $stmt->execute([$sid, $grandtotal, $cash, $change, $datetime, 'CH', $orderId]);
    $p->temp('info', "Bill paid for <b>$orderId</b>, Change: RM <b>$change</b>");

    // clear temporary session
    unset($_SESSION['order-reference']);
    unset($_SESSION['grandtotal']);
}

$p->redirect('../home.php');