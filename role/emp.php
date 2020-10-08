<?php
    include '../_base.php';
    $p->pagename = "Restaurant";
    $p->title = "Login";

    // TODO: read from HTTP session
    $user = $_SESSION['user'] ?? '';

    if ($p->post) {
        // TODO: write to HTTP session
        $btn = $p->req('btn');

        if ($btn == 'a' && $p->role == 'admin') {
            $p->redirect("login.php");
        }
    
        if ($btn == 's' && $p->role == 'staff') {
            $p->redirect("login.php");
        }

        $_SESSION['user'] = $user;
    }

    include '../_header.php';
?>
<form method="post">
    <div>
        <button name='btn' value='a'>Admin</button>
        </br>
        </br>
        <button name='btn' value='s'>Staff</button>
        </br>
        </br>
        <button data-get="selectRole.php">Back</button>
    </div>
</form>

<?php
    include '../_footer.php';