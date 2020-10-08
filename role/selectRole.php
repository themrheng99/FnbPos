<?php
    include '../_base.php';
    $p->pagename = "Restaurant";
    $p->title = "Login";

    include '../_header.php';
?>
<form method="post">
    <div>
        <button data-get="emp.php">Employee</button>
        </br>
        </br>
        <button data-get="cust.php">Customer</button>
    </div>
</form>

<?php
    include '../_footer.php';