<?php
include '_base.php';
$p->title = 'Control Panel';

$p->auth('admin, staff');

$name = $description = $price = '';

include '_header.php';
?>

<span class="info"><?= $p->temp('info') ?></span>
<br></br>

<div class="container">
    <div class="scontrolpanel">
        <div>
            <a href="/table/table-view.php" class="btn btn-primary">Order & Bill</a>
            <a href="/order/transaction-history.php" class="btn btn-primary">Transaction History</a>
            <a href="/report/report.php" class="btn btn-primary">Reports</a>
        </div>
        <div>
            <a href="/table/table-manage.php" class="btn btn-primary">Table Manage</a>
            <a href="/menu/menu-manage.php" class="btn btn-primary">Menu Manage</a>
            <a href="/order/kitchen.php" class="btn btn-primary">Order Status</a>
        </div>
    </div>
</div>

<?php include '_footer.php';