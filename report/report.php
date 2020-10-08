<?php
include '../_base.php';

$p->title = 'Reports';

$p->auth('admin, staff');

include '../_header.php';
?>

<a href="../controlpanel.php" class="btn btn-outline-info">Back</a> 
<br></br>

<div class="container">
    <a href="r1.php" class='btn btn-info'>Annual Sales Report</a>
    <a href="r2.php" class='btn btn-info'>Daily Sales Report</a>
</div>

<?php include '../_footer.php';