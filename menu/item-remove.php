<?php
include '../_base.php';

$p->auth('admin');

if ($p->post) {
    $id = $p->req('id');

    // check if item exist
    $stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
    $stmt->execute([$id]);
    $i = $stmt->fetchColumn();
    if (!$i) $p->redirect('/menu-manage.php');

    // check if item is been reference in order
    $stmt = $db->prepare('SELECT * FROM `order_item` WHERE `item` = ?');
    $stmt->execute([$id]);
    $i = $stmt->fetch();
    // if no reference
    if (!$i) {
        // delete from db
        $stmt = $db->prepare('SELECT image FROM item WHERE id = ?');
        $stmt->execute([$id]);
        $img = $stmt->fetchColumn();

        // remove relevant images (item image/ qr)
        unlink("../menu-image/".$img);
        unlink("../item-qr/".$id.".png");
        $stmt = $db->prepare('DELETE FROM item WHERE id = ?');
        $stmt->execute([$id]);
        $p->temp('info', "Item <b>$id</b> removed");
    
        // if got reference
    } else {
        // set to unlist. reserved
        $stmt = $db->prepare('UPDATE item SET unlist = 1 WHERE id = ?');
        $stmt->execute([$id]);
        $p->temp('info', "Item <b>$id</b> unlisted for reserve data");
    }
}

$p->redirect('menu-manage.php');