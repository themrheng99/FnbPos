<?php
include '../_base.php';
$p->title = 'Transaction History';

$p->auth('admin, staff');

$term = $p->get('term');

$stmt = $db->prepare('SELECT * FROM `order` WHERE id LIKE ? OR customer LIKE ? OR staff LIKE ?');
$stmt->execute(["%$term%", "%$term%", "%$term%"]);
$orders = $stmt->fetchAll();

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a> 
<input type="search" id="term" placeholder="Search Id/ Customer/ Staff" data-upper autofocus class="btn" style="width: 50%"> 
<br></br>

<div class="container">
    <p><?= count($orders) ?> record(s)</p>
    <div id="tables">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Id</th>
                    <th>Customer</th>
                    <th>Table</th>
                    <th>Staff</th>
                    <th>Amount Due (RM)</th>
                    <th>Pay (RM)</th>
                    <th>Change (RM)</th>
                    <th>Date Time</th>
                    <th>Status</th>
                </tr> 
            </thead>
            <tbody>
                <?php
                    foreach ($orders as $o) {
                        $str = "
                            <tr>
                                <td>$o->id</td>
                                <td>$o->customer</td>
                                <td>$o->table</td>
                                <td>$o->staff</td>
                                <td>$o->totalamount</td>
                                <td>$o->pay</td>
                                <td>$o->change</td>
                                <td>$o->datetime</td>
                                <td>{$STATUS_BILL[$o->status]}</td>
                            </tr>";
                        echo $str;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // searching
    let term;

    $('#term').on('input', function (e) { 
        term = $(this).val().trim();
        let url = `transaction-history.php?term=${term} #tables`;
        $('#tables').load(url);
    });

</script>

<?php include '../_footer.php';