<?php
include '../_base.php';

$p->auth('admin');

if ($p->post) {
    $id = $p->req('id');

    // only last inserted table can be remove
    // find different table types' last table id
    $stmt = $db->prepare('SELECT MAX(id) FROM `table` WHERE id LIKE ?');
    $arr = array_keys($TABLETYPE);
    $idarr = [];
    foreach ($arr as $ty) {
        $stmt->execute([$ty.'%']);
        $value = $stmt->fetchColumn();
        array_push($idarr, $value);
    }
    if (in_array($id, $idarr)) {
        $stmt = $db->prepare('SELECT * FROM `order` WHERE `table` = ?');
        $stmt->execute([$id]);
        $t = $stmt->fetch();
        if (!$t) {
            // remove table
            $stmt = $db->prepare('DELETE FROM `table` WHERE id = ?');
            $stmt->execute([$id]);
            $p->temp('info', "Table <b>$id</b> removed");
        } else {
            // unlist table
            $stmt = $db->prepare('UPDATE `table` SET unlist = 1 WHERE id = ?');
            $stmt->execute([$id]);
            $p->temp('info', "Table <b>$id</b> unlisted for reserving data");
        }
        
        $p->redirect('table-manage.php');
    }

}

$p->redirect('table-manage.php');