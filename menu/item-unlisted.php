<?php
include '../_base.php';

$p->title = "Unlisted Items";

$p->auth('admin');

// fetch unlisted items
$stmt = $db->query('SELECT * FROM `item` WHERE unlist = 1');
$items = $stmt->fetchAll();

include '../_header.php';
?>

<a href="menu-manage.php" class="btn btn-outline-info">Back</a><br></br>

<?php
// display all unlisted items
foreach ($items as $i) {
    $str = "
        <div class='menu-table'>
            <div class='row'>
                <div class='head'><p>$i->id</p></div>
            </div>
            <div class='row'>
                <div class='body'>
                    <image src='../menu-image/$i->image'>
                    <p><b>$i->name</b></p>
                    <p>RM <b>$i->price</b></p>
                    <div>
                    <button data-post='item-relist.php?id=$i->id' class='btn btn-info'><b>Restore</b></button>
                    </div>
                </div>
            </div>
        </div>
    ";
    echo $str;
}

include '../_footer.php';