<?php
include '../_base.php';
$p->title = 'Order & Bill';

$p->auth('admin, staff');

$stmt = $db->query('SELECT * FROM `table`');
$table = $stmt->fetchAll();

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a>
<br></br>

<div class="container">
    <div id="tables">
    <?php
        if (!$table) $str = '<h1>No table exist</h1>';
        else foreach ($table as $t) {
            $str = "<div class='tabledisplay'>";
            $i = substr($t->id, 1);

            $str .= "<a href='../order/order-bill.php?table=$t->id' class='{$STATUS_TABLE[$t->status]}'>{$TABLETYPE[$t->type]}<br> $i</a>";

            $str .= "</div>";
            echo $str;
        }

    ?>
    </div>
</div>

<script>
    // auto refresh 
    function auto_refresh() {
        $('#tables').load('table-view.php #tables');
    }
    // 5second auto refresh
    setInterval(() => {
        auto_refresh();
    }, 5000);
</script>

<?php include '../_footer.php';