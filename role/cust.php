<?php
    include '../_base.php';
    $p->pagename = "Restaurant";
    $p->title = "Login";

    // TODO: read from HTTP session
    $user = $_SESSION['user'] ?? '';

    if ($p->post) {
        // TODO: write to HTTP session
        $btn = $p->req('btn');

        if ($btn == 'm' && $p->role == 'member') {
            $p->redirect("login.php");
        }
    
        if ($btn == 'g' && $p->role == 'guest') {
            $p->redirect("/.php");
        }

        $_SESSION['user'] = $user;
    }

    include '../_header.php';
?>
<form method="post">
    <div>
        <button name='btn' value='m'>Member</button>
        </br>
        </br>
        <button name='btn' value='g'>Guest</button>
        </br>
        </br>
        <span><a href="../security/register.php">Register as a memeber?</a></span>
        <button data-get="selectRole.php">Back</button>
    </div>
</form>

<?php
    include '../_footer.php';