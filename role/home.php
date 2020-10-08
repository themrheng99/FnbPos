<?php
include '../_base.php';
$p->title = 'Home';

$user = $_SESSION['user'];
$role = $_SESSION['role'];

include '../_header.php';
?>

<?php
    if ($role === 'staff') {
        echo "STAFF LOGGED";
    }
    if ($role === 'customer') {
        echo "CUSTOMER LOGGED";
	echo $user;
	echo $role;
    }
?>

<?php include '../_footer.php';