<?php
include '../_base.php';

$p->auth('admin');

if ($p->post) {
    $id = $p->req('id');

    // restore unlisted item
    $stmt = $db->prepare('UPDATE `item` SET unlist = 0 WHERE id = ?');
    $stmt->execute([$id]);
    $p->temp('info', "Item <b>$id</b> restored");
    $p->redirect('menu-manage.php');
}

$p->redirect('menu-manage.php');