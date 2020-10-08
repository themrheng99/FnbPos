<?php
include '_base.php';

//$p->auth('customer');

var_dump($p->role);
var_dump($p->order);

$oid = $p->order;
$totalAmount = $_SESSION['grandtotal'];
$datetime = date('Y-m-d H:i:s');
$stmt = $db->prepare('UPDATE `order` SET totalamount = ?, pay = ?, `change` = ?, datetime = ?, status = 1, payment = ? WHERE id = ?');
$stmt->execute([$totalAmount, $totalAmount, 0, $datetime, 'PP', $oid]);
unset($_SESSION['grandtotal']);
$p->temp('info', "RM <b>$totalAmount</b> paid! Thank you :D");
//$p->redirect('home.php');