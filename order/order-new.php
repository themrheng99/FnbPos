<?php
include '../_base.php';

$p->auth('admin, staff');
$p->title = "Create New Order";
$table = $p->req('table');

include '../_header.php';

if ($p->post) {
    // generate id
    $stmt = $db->query('SELECT MAX(id) FROM `order`');
    $max = $stmt->fetchColumn();
    $next = sprintf("%08d", (substr($max, 2) + 1));
    $oid = 'TX' . $next;
    
    // create order
    $stmt = $db->query("INSERT INTO `order` (id, `table`) VALUES ('$oid', '$table')");
    $p->redirect("order-bill.php?table=$table");
}

if ($table == $_SESSION['order-table']) {
    $str = "<form method='POST'>";
    $str .= "<p>No order active on Table ".$table.". Do you want to create an order?</p>";
    $str .= "<button type='submit' class='btn btn-success'>Yes</button>";
    $str .= "<button data-get='../table/table-view.php' class='btn btn-success'>No</button>";
    $str .="</form>";
    echo $str;
} else $p->redirect('../home.php');


include '../_footer.php';
?>