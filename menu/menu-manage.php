<?php
include '../_base.php';
$p->title = 'Menu Manage';

$p->auth('admin, staff');

$term = $p->get('term');

$stmt = $db->prepare('SELECT * FROM item WHERE (name LIKE ? OR id LIKE ?) AND unlist = 0');
$stmt->execute(["%$term%", "%$term%"]);
$items = $stmt->fetchAll();

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a> 
<?php 
if ($p->role == 'admin') {
    echo "<a href='item-unlisted.php' class='btn btn-outline-info'>Restore Unlisted Item(s)</a> ";
    echo "<a href='item-add.php' class='btn btn-info'>Add New Item</a> ";
}
?>

<a href="item-qr.php?fn=all" target="_blank" class="btn btn-info">Print Menu with QR</a>
<input type="search" id="term" placeholder="Search (Id or Name)" data-upper autofocus class="btn">
<br></br>

<span class="info"><?= $p->temp('info') ?></span>
<br></br>
<div id="target">
<?php
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
                        <a href='item-qr.php?fn=single&id=$i->id' target='_blank' class='btn btn-info'><b>QR</b></a>
        ";
        // only admin can use these functions
        if ($p->role == 'admin')
            $str .= "
                <button data-get='item-edit.php?id=$i->id' class='btn btn-warning'><b>EDIT</b></button>
                <button data-post='item-remove.php?id=$i->id' class='btn btn-danger'><b>REMOVE</b></button>

            ";
                        
        $str .= "
                        </div>
                    </div>
                </div>
            </div>
        ";
        echo $str;
    }
    
?>
</div>

<script>
    // searching
    let term;

    $('#term').on('input', function (e) { 
        term = $(this).val().trim();
        let url = `menu-manage.php?term=${term} #target`;
        $('#target').load(url);
    });

</script>

<?php include '../_footer.php';