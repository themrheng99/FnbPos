<?php
include '_base.php';
$p->title = 'Home';

$user = $p->user;
$role = $p->role;

// if user no login
// ask to rescan table qrcode to get in login page
if (!$user) $p->redirect('/scan-again.php');

include '../_header.php';
?>

<?php
    if ($role === 'staff' || $role === 'admin') {
        $p->redirect('controlpanel.php');
    }
    if ($role === 'customer') {
        $p->redirect('/menu/menu.php');
    }
?>

<?php include '_footer.php';