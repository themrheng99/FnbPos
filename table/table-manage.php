<?php
include '../_base.php';
$p->title = 'Table Manage';

$p->auth('admin, staff');

// get tables from db
$stmt = $db->query('SELECT * FROM `table` WHERE unlist = 0');
$table = $stmt->fetchAll();

// get last table id of each tabletype
$stmt = $db->prepare('SELECT MAX(id) FROM `table` WHERE id LIKE ?');
$arr = array_keys($TABLETYPE);
$idarr = [];
foreach ($arr as $ty) {
    $stmt->execute([$ty.'%']);
    $value = $stmt->fetchColumn();
    array_push($idarr, $value);
}

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a> 
<?php
if ($p->role == 'admin')
    echo "<a href='table-unlisted.php' class='btn btn-outline-info'>Restore Unlisted Table(s)</a> ";
?>
<br></br>

<div class="container">
    <div id="tables">
        <table class="table a">
            <thead>
                <tr>
                    <th>Id</th>
                    <th></th>
                    <th>Status</th>
                    <th class=><span class="info"><?= $p->temp('info') ?></span></th>
                </tr> 
            </thead>
            <tbody>
                <?php
                    foreach ($table as $t) {
                        $i = substr($t->id, 1);
                        $str = "
                            <tr class={$STATUS_TABLE[$t->status]}>
                                <td>$t->id</td>
                                <td>{$TABLETYPE[$t->type]} $i</td>
                                <td>{$STATUS_TABLE[$t->status]}</td>
                                <td>
                                    <a href='table-qr.php?id=$t->id' target='_blank' class='btn btn-info'>Gen. QR</a>
                                    <button data-post='table-edit.php?id=$t->id' class='btn btn-warning'>Change Status</button>
                        ";
                        // add remove button if it is last id for certain table type
                        if (in_array($t->id, $idarr)) {
                            if ($p->role == 'admin')
                                $str .= "<button data-post='table-remove.php?id=$t->id' class='btn btn-danger'>Remove</button>";
                        }
                        $str .= "</td></tr>";
                        echo $str;
                    }
                ?>
                
                        <?php
                            if ($p->role == 'admin') {
                                // generate add table button for each table type
                            $str = '
                            <tr>
                                <td colspan="4" class="table-light">
                                    <h5 style="font-weight: bold">Add Table</h5>
                                    <h6 style="font-weight: bold">Table types</h6>
                            ';
                            foreach ($arr as $ty) {
                                $str .= "<button data-post='table-add.php?type=$ty' class='btn btn-success'>{$TABLETYPE[$ty]}</button> ";
                            }
                            echo $str;
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function auto_refresh() {
        $('#tables').load('table-manage.php #tables');
    }

    setInterval(() => {
        auto_refresh();
    }, 5000);
</script>

<?php include '../_footer.php';