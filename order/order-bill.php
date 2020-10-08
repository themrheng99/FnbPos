<?php
include '../_base.php';

$p->auth('customer, admin, staff');

$stm = $db->prepare("SELECT * FROM `order_item` WHERE `order` = ?");

if ($p->role != 'customer') {
    // for admin or staff
    // get input table id
    // check table existancy
    // generate HTML Title
    // fetch latest unpaid order (active)
    // assign table id as temp session
    // if no order is exist, ask for create new order
    $id = $p->req('table');

    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$id]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('../table/table-manage.php');
    $num = substr($t->id, 1);
    $p->title = 'Order & Bill - ' . $TABLETYPE[$t->type] . ' ' . $num;
    
    $stmt = $db->prepare('SELECT MAX(id) FROM `order` WHERE `table` = ? AND status = ?');
    $stmt->execute([$id, 0]);
    $orderId = $stmt->fetchColumn();

    $_SESSION['order-table'] = $id;
    if (!$orderId) $p->redirect("order-new.php?table=$id");
    $stm->execute([$orderId]);
} else {
    // for customer, get order id from session
    $p->title = 'Order & Bill - ' . $TABLETYPE[substr($p->table, 0, 1)] . ' ' . substr($p->table, 1);
    $orderId = $p->order;
    $stm->execute([$orderId]);
}

$orders = $stm->fetchAll();

$stmt1 = $db->prepare('SELECT `table` FROM `order` WHERE id = ?');
$stmt1->execute([$orderId]);
$table = $stmt1->fetchColumn();
$stmt2 = $db->prepare('SELECT * FROM item WHERE id = ?');

$orderCount = 0;
$cash = $check = '';
$total = $grandtotal = 0.00;

$v->rules = [
    'cash' => [
        'required' => true, 
        'maxlength' => 1000,
        'minlength' => 0,
    ],
    'check' => [
        'required' => true,
    ],
];



include '../_header.php';
?>

<a href="../table/table-view.php" class="btn btn-outline-info">Back</a> 
<?php
    if ($p->role != 'customer') {
        echo "<a href='../menu/menu.php?table=".$id."' class='btn btn-info'>Add Order</a> ";
    }
?>
<a href="order-confirmation.php<?php if ($p->role != 'customer') echo '?table='.$id; ?>" class="btn btn-warning">Confirm Your Order(s)</a> 
<a href='#bill' class='btn btn-success'>Pay Bill</a>

<br></br>

<div class="container order">
    <h3>Order</h3>
    <?php
        if ($p->role == 'customer')
        echo "<div id='targets'>";
        if ($orders)
        foreach ($orders as $o) {
            $stmt2->execute([$o->item]);
            $it = $stmt2->fetch();
            $str = "
                <div class='menu-table'>
                    <div class='row'>
                        <div class='head'><p>$it->id</p></div>
                    </div>
                    <div class='row'>
                        <div class='body'>
                            <image src='../menu-image/$it->image'>
                            <p><b>$it->name</b></p>
                            <p>RM <b>$it->price</b> each <span style='float: right'>Qty: <b>$o->quantity</b></span></p>
                            <p class='status {$STATUS_ORDER[$o->status]}'>{$STATUS_ORDER[$o->status]}</p>
                        </div>
                    </div>
                </div>
            ";
            echo $str;
            $orderCount++;
        }
        if ($p->role == 'customer')
        echo "</div>";
    ?>
    <br></br>
</div>

<div class="container bill" id="bill">
    <h3>Bill</h3>
    <div>
        <table class='table table-light'>
            <thead class='thead-light'>
                <tr>
                    <th>Item Id</th>
                    <th>Name</th>
                    <th>Price Each (RM)</th>
                    <th style='text-align: center'>Quantity</th>
                    <th style='text-align: left'>Subtotal (RM)</th>
                </tr> 
            </thead>
            <tbody>
            <?php
                if ($orders)
                foreach ($orders as $o) {
                    $stmt1->execute([$o->order]);
                    $or = $stmt1->fetch();
                    $stmt2->execute([$o->item]);
                    $it = $stmt2->fetch();
                    $total += $o->subtotal;
                    $str = "
                        <tr>
                            <td>$it->id</td>
                            <td>$it->name</td>
                            <td>$it->price</td>
                            <td style='text-align: center'>$o->quantity</td>
                            <td style='text-align: left'>$o->subtotal</td>
                        </tr>
                    ";

                    echo $str;         
                }
                $str = "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: right'>Total Amount:</td>
                        <td style='text-align: left'>
                ";
                
                $str .= sprintf('%.2f', $total);
                
                $str .= "
                    </td>
                    </tr>
                ";

                $str .= "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: right'>Service Tax:</td>
                        <td style='text-align: left'>
                ";
                
                $str .= sprintf('%.2f', $total * $TAX);
                
                $str .= "
                    </td>
                    </tr>
                ";

                $str .= "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: right'>Grand Total:</td>
                        <td style='text-align: left'>
                ";

                $grandtotal = ($total + ($total * $TAX));
                
                $str .= sprintf('%.2f', $grandtotal);
                
                $str .= "
                    </td>
                    </tr>
                ";

                echo $str;
            ?>
            </tbody>
        </table>
    </div>
    <br></br>
    <?php
        if ($p->role == 'customer') {
            $_SESSION['grandtotal'] = $grandtotal;
            echo "
                <form action='../paypal-payment.php' method='post'>
                    <input type='hidden' name='cmd' value='_xclick' />
                    <input type='hidden' name='no_note' value='1' />
                    <input type='hidden' name='lc' value='UK' />
                    <input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest' />
                    <input type='hidden' name='first_name' value='". $p->user ."'
                    <input type='hidden' name='payer_email' value='customer@example.com' />
                    <input type='hidden' name='item_number' value='123456' />
                    <input type='submit' name='submit' value='Pay with Paypal Now' class='btn btn-info'>
                </form>
                <br>
            ";
        }

        if (in_array($p->role, $STAFF_ROLE)) {
            $str = "<form action='bill.php' method='POST' class='form-bill'>";
            // set temp session
            $_SESSION['order-reference'] = $orderId;
            $_SESSION['grandtotal'] = $grandtotal;
            $str .= $h->label('cash');
            $str .= $h->number('cash', null, $grandtotal, 1000, 0.01, 'autofocus');
            $str .= $v->error('cash');
    
            if ($orderCount) {
                $str .= "<br></br>";
                $str .= $h->label('check', "Some item(s) no yet served. Are you sure to continue?");
                $str .= $h->checkbox('check', null, 'Yes');
                $str .= $v->error('check');
                $str .= "<br></br>";
            } else {
                $str .= "<br></br>";
                $str .= $h->label('check', "Confirm?");
                $str .= $h->checkbox('check', null, 'Yes');
                $str .= $v->error('check');
                $str .= "<br></br>";
            }
    
            $str .= "<div><input type='submit' value='Pay Now' class='btn btn-success'></div>";
            $str .= "</form><br></br>";
            echo $str;
        }
    ?>
</div>

<script>
function scroll() {
    window.scrollTo(0,document.body.scrollHeight);
}

</script>

<script>
    $('form').validate({
        rules,
        messages,
        ignore: ''
    });
    function auto_refresh() {
        $('#targets').load('order-bill.php #targets');
    }

    setInterval(() => {
        auto_refresh();
    }, 5000);
</script>

<?php include '../_footer.php';