<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $p->title ?></title>
    <link rel="shortcut icon" href="/image/favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.validate.js"></script>
    <script src="/js/additional-methods.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        // initialize client-side validation rules and messages
        $.extend($.validator.messages, <?= json_encode($v->defaults) ?>);
        let rules = processRules(<?= json_encode($v->rules) ?>);
        let messages = <?= json_encode($v->messages) ?>;
    </script>
    <?= $p->head ?>
</head>
<body>
    <header>
        <h1 class="header"><a href="/home.php">DT Restaurant</a></h1>
        <div class="user-display">
            <span class="h">Username: <b><?= $p->user ?></b></span>
            <?php 
                if ($p->role == 'customer') {
                    echo "
                        <span class='h'>Table: <b>$p->table</b></span>
                        <span class='h'>Order ref.: <b>$p->order</b></span>
                    ";
                    
                } else {
                    echo "<span class='h'>Role: <b>$p->role</b></span>";
                }
                if ($_SESSION['photo'] ?? null) {
                    echo "<img src='photo/{$_SESSION['photo']}'>";
                }
            ?> 
        </div>
    </header>
    <nav class="header-nav">
        <a href="/home.php">Home</a>
        <?php
            if ($p->role == 'admin') {
                echo "
                    <a href='../maintenance/adminTable.php'>Admin Table</a>
                    <a href='../maintenance/staffTable.php'>Staff Table</a>
                    <a href='../maintenance/customerTable.php'>Customer Table</a>
                    <a href='../maintenance/profileEditAdmin.php'>Admin profile</a>
                    <a href='../security/registerAdmin.php'>Register Admin</a>
                    <a href='../security/registerStaff.php'>Register Staff</a>
                    <a href='../security/registerCustomer.php'>Register Customer</a>
                    <a href='../dataReport/statusChart.php'>Status Report</a>
                    <a href='../dataReport/totalUserChart.php'>Num.User Report</a>
                    <a href='../security/password.php'>Password</a>
                    <a href='../security/logout.php'>Logout</a>
                ";
            }
            else if ($p->role == 'staff') {
                echo "
                    <a href='../table/table-manage.php'>Table Manage</a>
                    <a href='../table/table-view.php'>Table View</a>
                    <a href='../order/kitchen.php'>Order Status</a>
                    <a href='../maintenance/customerTable.php'>Customer Table</a>
                    <a href='../maintenance/profileEditStaff.php'>Staff profile</a>
                    <a href='../security/password.php'>Password</a>
                    <a href='../security/logout.php'>Logout</a>
                ";
            }
            else if ($p->role == 'customer') {
                if ($p->user == 'GUEST') {
                    echo "
                        <a href='../menu/scan-menu-qr.php'>Scan Menu QR</a>
                        <a href='../order/order-confirm.php'>Confirm your order(s)</a>
                        <a href='../security/logout.php'>Logout</a>
                    ";
                } else {
                    echo "
                        <a href='../menu/scan-menu-qr.php'>Scan Menu QR</a>
                        <a href='../order/order-confirm.php'>Confirm your order(s)</a>
                        <a href='../maintenance/profileEditCustomer.php'>Profile</a>
                        <a href='../security/password.php'>Password</a>
                        <a href='../security/logout.php'>Logout</a>
                    ";
                }
                
            }
            else {
                echo "
                    <a href='../security/registerCustomer.php'>Register Customer</a>
                    <a href='../security/login.php'>Login</a>
                ";
            }
        ?>
    </nav>
    <main>
        <h1><?= $p->title ?></h1>