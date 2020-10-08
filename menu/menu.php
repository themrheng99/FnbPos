<?php
include '../_base.php';
$p->title = 'Menu';

$p->auth('customer, admin, staff');

// if admin or staff, get input table, check existancy
if ($p->role != 'customer') {
    $table = $p->req('table');
    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$table]);
    $t = $stmt->fetch();
    if (!$t) $p->redirect('../home.php');
}

$term = $p->get('term');
$category = $p->get('category', '');

// fetch item that met the search term else all
// fetch item that met the filter term else all
$stmt = $db->prepare("SELECT * FROM item WHERE (name LIKE ? OR id LIKE ?) AND (category = ? OR ? = '')");
$stmt->execute(["%$term%", "%$term%", $category, $category]);
$items = $stmt->fetchAll();

// get all available category
$stm = $db->query('SELECT DISTINCT id FROM category ORDER by id');
$cats = $stm->fetchAll(PDO::FETCH_COLUMN);

include '../_header.php';
?>

<?php 
    // for customer homepage is menu, so no 'back' button is prepared

    if ($p->role != 'customer') {
        echo "<a href='../controlpanel.php' class='btn btn-outline-info'>Back</a> ";
    }
    
    if ($p->role == 'customer') {
        echo "
            <a href='scan-menu-qr.php' class='btn btn-info'>Scan Menu QR</a>
            <a href='../order/order-confirmation.php' class='btn btn-warning'>Confirm Your Order(s)</a>
            <a href='../order/order-bill.php' class='btn btn-success'>Order(s) Status & Bill</a>
            <br></br>
        ";
    }
        
?>
<input type="search" id="term" placeholder="Search (Id or Name)" data-upper autofocus class="btn"> 
<p class='mt-1'>
    <a href="?">All</a>
    <?php
        foreach ($cats as $c) {
            echo "| <a href='?category=$c'>{$CATEGORIES[$c]}</a>";
        }
    ?>
</p>

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
                        <button data-get='../order/order.php?id=$i->id
        ";

        if ($p->role != 'customer') $str .= "&table=".$table;
        
        $str .= "' class='btn btn-primary'><b>ADD TO ORDER</b></button>
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
        let url = window.location.href;
        if (url.substring(url.length - 1) != `p`) {
            url += `&term=${term} #target`;
        } else url += `?term=${term} #target`;
        $('#target').load(url);
    });

</script>

<?php include '../_footer.php';