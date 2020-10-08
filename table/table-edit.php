<?php
include '../_base.php';

$p->auth('admin, staff');

$id = $status = '';

if ($p->post) {
    // check table existancy
    $id = $p->req('id');
    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$id]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('table-manage.php');

    // if status is false, change to true, else true to false
    // where table (0 = Available, 1 = In Use)
    $state = $t->status ? FALSE : TRUE;

    $stmt = $db->prepare('UPDATE `table` SET status = ? WHERE id = ?');
    $stmt->execute([$state, $id]);

    $p->temp('info', "Status changed for <b>$id</b>");

}

$p->redirect('table-manage.php');