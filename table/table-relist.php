<?php
include '../_base.php';

$p->auth('admin');

if ($p->post) {
    // restore unlisted table

    $id = $p->req('id');

    $stmt = $db->prepare('UPDATE `table` SET unlist = 0 WHERE id = ?');
    $stmt->execute([$id]);
    $p->temp('info', "Table <b>$id</b> restored");
    $p->redirect('table-manage.php');
}

$p->redirect('table-manage.php');