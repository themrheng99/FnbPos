<?php
include '../_base.php';
$p->title = 'Confirmation of Order(s)';

$p->auth('customer, admin, staff');

if ($p->role != 'customer') {
    // for admin or staff, get table id from input
    $table = $p->req('table') ;
    $stmt = $db->prepare('SELECT MAX(id) FROM `order` WHERE `table` = ? AND status = ?');
    $stmt->execute([$table, 0]);
    $id = $stmt->fetchColumn();
    if (!$id) $p->redirect('../home.php');
} else {
    $id = $p->order ?? null;
}

// if table not exist redirect to home
if (!$id) $p->redirect('../home.php');

// get all order_item belong the the order
$stmt = $db->prepare('SELECT * FROM `order_item` WHERE `order` = ? AND status = ?');
$stmt->execute([$id, 0]);
$orders = $stmt->fetchAll();

// get all item from order_item that is 'PENDING' to be confirm
$stmt = $db->prepare('SELECT item FROM `order_item` WHERE `order` = ? AND status = ?');
$stmt->execute([$id, 0]);
$arr = $stmt->fetchAll(PDO::FETCH_COLUMN);

// if item exist
if ($arr) {
    $in = str_repeat('?,', count($arr) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM item WHERE id IN ($in)");
    $stmt->execute($arr);
    $items = $stmt->fetchAll();
} else $items = '';


include '../_header.php';
?>

<a href='../home.php' class='btn btn-outline-info'>Back</a>
<br></br>


<?php
    if ($items)
    foreach ($orders as $o) {
        foreach ($items as $i) {
            if ($o->item == $i->id) {
                $str = "
                <div class='menu-table'>
                    <div class='row'>
                        <div class='head'><p>$i->id</p></div>
                    </div>
                    <div class='row'>
                        <div class='body'>
                            <image src='../menu-image/$i->image'>
                            <p><b>$i->name</b></p>
                            <p>RM <b>$i->price</b> each <span style='float: right'>Qty: <b>$o->quantity</b></span></p>
                ";

                // for customer
                if ($p->role == 'customer') {
                    $str .= "
                        <button data-get='order-edit.php?item=$i->id&oid=$o->id' class='btn btn-warning'><b>EDIT</b></button>
                        <button data-post='order-remove.php?item=$i->id&oid=$o->id' class='btn btn-danger'><b>REMOVE</b></button>
                        <button data-get='order-confirm.php?item=$i->id&oid=$o->id' class='btn btn-info'><b>CONFIRM NOW</b></button>
                    ";
                } else {
                    // for admin or staff
                    // table as an input
                    $str .= "
                        <button data-get='order-edit.php?table=$table&item=$i->id&oid=$o->id' class='btn btn-warning'><b>EDIT</b></button>
                        <button data-post='order-remove.php?table=$table&item=$i->id&oid=$o->id' class='btn btn-danger'><b>REMOVE</b></button>
                        <button data-get='order-confirm.php?table=$table&item=$i->id&oid=$o->id' class='btn btn-info'><b>CONFIRM NOW</b></button>
                    ";
                }

                $str .="    
                        </div>
                    </div>
                </div>
                ";
    
                echo $str;
            }
        }
    }
    
?>


<?php include '../_footer.php';