<?php
include '../_base.php';

$p->auth('admin');

$id = $type = '';

if ($p->post) {
    // get table type, check type existancy
    $type = $p->req('type');
    $stmt = $db->prepare('SELECT * FROM `tabletype` WHERE id = ?');
    $stmt->execute([$type]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('table-manage.php');
    
    // generate id
    $max = $db->query("SELECT MAX(id) FROM `table` WHERE type = '$type'")->fetchColumn();
    $next = substr($max, 1) + 1;
    $id = sprintf($type . '%03d', $next);

    // insert to db
    $stmt = $db->prepare('INSERT INTO `table` (id, type) VALUES (?, ?)');
    $stmt->execute([$id, $type]);
    $p->temp('info', "Table inserted as <b>$id</b>");

}

$p->redirect('table-manage.php');