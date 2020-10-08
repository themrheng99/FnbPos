<?php
include '../_base.php';
$p->title = 'Logout';

$p->auth('customer, admin, staff');

if ($p->role == 'customer') {
    $stmt = $db->prepare('UPDATE `table` SET status = ? WHERE id = ?'); //for change table status
    $stmt->execute(['0', $p->table]);
}

unset($_SESSION['photo']);
$p->logout('../signout.php');
