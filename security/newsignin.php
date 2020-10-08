<?php 
include '../_base.php';
$p->auth('customer');

$t = $p->get('table') ? $p->get('table') : '';

$stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
$stmt->execute([$t]);
$t = $stmt->fetch();

if (!$t) $p->redirect('tablenotfound.html');
$_SESSION['table'] = $t;

include '../_header.php';
?>

<div class="container">
    <button data-get="/login.php?u=guest" class="btn btn-info">GUEST</button>
    <button data-get="/login.php?u=member" class="btn btn-info">MEMBER</button>

    <a href="/signup.php">Register as member?</a>
</div>

<?php include '../_footer.php';