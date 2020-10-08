<?php
include '../_base.php';

$p->title = "Unlisted Table";

$p->auth('admin');

$stmt = $db->query('SELECT * FROM `table` WHERE unlist = 1');
$tables = $stmt->fetchAll();

include '../_header.php';
?>

<a href="table-manage.php" class="btn btn-outline-info">Back</a><br></br>

<?php

$str = "
    <table class='table'>
        <thead>
            <tr>
                <th>Id</th>
                <th></th>
            </tr> 
        </thead>
        <tbody>
    ";

if (!$tables) {
    echo "No unlisted table";
} else {
    
    foreach ($tables as $t) {
        $i = substr($t->id, 1);
        $str .= "
            <tr class={$STATUS_TABLE[$t->status]}>
                <td>$t->id</td>
                <td>{$TABLETYPE[$t->type]} $i</td>
                <td>{$STATUS_TABLE[$t->status]}</td>
                <td>
                    <button data-post='table-relist.php?id=$t->id' class='btn btn-info'>Restore</button>
                </td>
            </tr>
        ";
    }
}

$str .= "
        </tbody>
        </table>
    ";

echo $str;
?>

<?php include '../_footer.php';