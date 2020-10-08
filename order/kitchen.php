<?php
include '../_base.php';

$p->auth('admin, staff');

$p->title = 'Order(s) Status';

// fetch all latest order that unpaid (active)
$stmt = $db->query('SELECT id FROM `order` WHERE status = 0');
$arr = $stmt->fetchAll(PDO::FETCH_COLUMN);

// if orders exist
if ($arr) {
    // fetch all order_item
    $in = str_repeat('?,', count($arr) - 1) . '?';
    $stmt = $db->prepare("SELECT * FROM `order_item` WHERE (status = 1 OR status = 2 OR status = 3) AND `order` IN ($in)");
    $stmt->execute($arr);
    $orders = $stmt->fetchAll();
} else $orders = '';

$stmt1 = $db->prepare('SELECT `table` FROM `order` WHERE id = ?');
$stmt2 = $db->prepare('SELECT `name` FROM item WHERE id = ?');

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a> 
<form action='order-status-update.php' method='post' id='f' style='display: inline'>
    <button data-check="ids[]" class="btn btn-outline-success">Check All</button>
    <button data-uncheck="ids[]" class="btn btn-outline-danger">Uncheck All</button>
    <button name="v" value="1" type="submit" class="btn btn-info">Confirmed</button>
    <button name="v" value="2" type="submit" class="btn btn-warning">Preparing</button>
    <button name="v" value="3" type="submit" class="btn btn-success">Served</button>
</form>

<br></br>
<span class="info"><?= $p->temp('info') ?></span>
<br></br>

<div id="tables">
    <table class="table a">
        <thead>
            <tr>
                <th></th>
                <th>Order Id</th>
                <th>Item Id</th>
                <th>Item Name</th>
                <th>Remark*</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Table</th>
            </tr> 
        </thead>
        <tbody>
            <?php
                if ($orders)
                foreach ($orders as $o) {
                    // get order table
                    $stmt1->execute([$o->order]);
                    $or = $stmt1->fetch();
                    // get order item
                    $stmt2->execute([$o->item]);
                    $it = $stmt2->fetch();

                    $str = "
                        <tr class={$STATUS_ORDER[$o->status]} data-row-check>
                            <td><input type='checkbox' name='ids[]' value='$o->id' form='f'></td>
                            <td>$o->id</td>
                            <td>$o->item</td>
                            <td>$it->name</td>
                            <td>$o->remark</td>
                            <td style='text-align: center'>$o->quantity</td>
                            <td>{$STATUS_ORDER[$o->status]}</td>
                            <td>$or->table</td>
                        </tr>

                    ";
                    
                    echo $str;
                }
            ?>

        </tbody>
    </table>
</div>

<script>
    // auto refresh page
    function auto_refresh() {
        $('#tables').load('kitchen.php #tables');
    }
    // 10second auto refresh
    setInterval(() => {
        auto_refresh();
    }, 10000);
</script>

<script>
    // if row is clicked, checkbox is checked
    $(document).on('change', "[name='ids[]']", function (e) {
        $(this)
            .parents('tr')
            .toggleClass('checked', this.checked);
    });

    $(function () {
        $("[name='ids[]']").trigger('change');
    });
</script>


<?php include '../_footer.php';