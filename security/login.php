<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<!-- cannot remove else will get error -->
<?php
include '../_base.php';

$username = $password = $captcha = $attmp = '';

if (isset($_SESSION["locked"]))
{
	$difference = time() - $_SESSION["locked"];
	if ($difference > 30)
	{
		unset($_SESSION["locked"]);
		unset($_SESSION["login_attempts"]);
	}
}

$p->title = 'Login';

if ($_SESSION['table']) {
    $t = $_SESSION['table'] ?? null;
    $u = $p->get('u') ;
    
    $stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
    $stmt->execute([$t->id]);
    $t = $stmt->fetch();
    
    // statement0 use - record if table is seated
    $stmt0 = $db->prepare('UPDATE `table` SET status = ? WHERE id = ?'); //for change table status.
    
    // statement1 use - create order reference on login
    $stmt = $db->query("SELECT MAX(id) FROM `order` WHERE `table` = '$t->id'");
    $max = $stmt->fetchColumn();

    $stmt = $db->query("SELECT MAX(id) FROM `order`");
    $m = $stmt->fetchColumn();
    $next = sprintf("%08d", (substr($m, 2) + 1));
    $oid = 'TX' . $next;
    $stmt1 = $db->prepare('INSERT INTO `order` (id, customer, `table`) VALUE (?, ?, ?)');
    
    // statement2 use - ensure last bill is paid, if the customer logout
    $stmt2 = $db->prepare('SELECT * FROM `order` WHERE id = ? AND `table` = ?');
    $stmt2->execute([$max, $t->id]);
    $lastorder = $stmt2->fetch();
    
    // statement3 use - if user logout and login again will change `customer` in `order`
    $stmt3 = $db->prepare('UPDATE `order` SET customer = ? WHERE id = ?');

    if (!$t) $p->redirect('/table/tablenotfound.html');
}

$stmt = $db->prepare('SELECT * FROM user WHERE username = ? AND password = SHA1(?)');

if ($u == 'guest') {
    $stmt0->execute(['1', $t->id]); //table status changed to seated.
    
    $stmt->execute(['GUEST', 'GUEST']);
    $user = $stmt->fetch();

    // if last order is unpaid load back, else create new.
    if ($lastorder) {
        if ($lastorder->status == 1) {  
            $stmt1->execute([$oid, $user->id, $t->id]);
        } else {
            $oid = $lastorder->id;
            $stmt3->execute([$user->id, $oid]);
        }
    } else {
        $stmt1->execute([$oid, $user->id, $t->id]);
    }
    
    $p->login($user->username, $user->role, $t->id, $oid, '/home.php');
}


$v->rules = [
    'username' => ['required' => true],
    'password' => ['required' => true],
    'captcha'  => ['required' => true],
];

if ($p->post) {
    $username = $p->req('username');
    $password = $p->req('password');
    $captcha = $p->req('captcha');

    $v->validate();

    if ($v->valid()) {
        $stmt->execute([$username, $password.'FNB']);
        $user = $stmt->fetch();
        var_dump($user);
    
        if ($user && $user->role =='customer' && $_SESSION['table']) {
            if($_SESSION['get_captcha_value'] == $_POST['captcha']){
                $_SESSION['photo'] = $user->photo;
                $stmt0->execute(['1', $t->id]); //table status changed to in use.
            
            // if last order is unpaid load back else create new
            if ($lastorder) {
                if ($lastorder->status == 1) {  
                    $stmt1->execute([$oid, $user->id, $t->id]);
                } else {
                    $oid = $lastorder->id;
                    $stmt3->execute([$user->id, $oid]);
                }
            } else {
                $stmt1->execute([$oid, $user->id, $t->id]);
            }

            $p->login($user->username, $user->role, $t->id, $oid, '/home.php');
            }else{
                $_SESSION["login_attempts"] += 1;
                $attmp++;
                $v->errors['captcha'] = 'Not matched. Please try again';
                echo "<script>alert('Invalid verification code');</script>" ;
            }
        } else if ($user && $user->role =='customer' && !$_SESSION['table']) {
            $p->redirect('../scan-again.php');

        } else if ($user && $user->role != 'customer') {
            if($_SESSION['get_captcha_value'] == $_POST['captcha']){
                $_SESSION['photo'] = $user->photo;
                $p->login($user->username, $user->role, null, null, '/home.php');
            }else{
                $_SESSION["login_attempts"] += 1;
                $attmp++;
                $v->errors['captcha'] = 'Not matched. Please try again';
                echo "<script>alert('Invalid verification code');</script>" ;
            }

        } else {
            $_SESSION["login_attempts"] += 1;
            $attmp++;
            $v->errors['username'] = 'Not matched';
            $v->errors['password'] = 'Not matched';
        }
        
    }

    if($attmp == 3){
        echo "<script>alert('Your number of login reach the limit');</script>" ;
    }
}

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<p class="info"><?= $p->temp('info') ?></p>

<form class="form" method="post">
    <?= $h->label('username') ?>
    <?= $h->text('username', null, 10, 'autofocus data-upper') ?>
    <?= $v->error('username') ?>

    <?= $h->label('password') ?>
    <?= $h->password('password', null, 10) ?>
    <?= $v->error('password') ?>

    <?= $h->label('Verification code') ?>
    <?= $h->text('captcha', null, 6) ?>
    <div>
        <img src="../security/captcha.php" />
        <?= $v->error('captcha') ?>
    </div>

    <div>   
        <?php
            if ($_SESSION["login_attempts"] >= 3)
            {
                $_SESSION["locked"] = time();
                echo "<script>alert('Please wait for 30 seconds');</script>";
                $p->redirect('../waiting.php');
            }
        ?>
            <button type="submit" name="submit">Login</button>  
            <button type="reset">Reset</button> 
    </div>
</form>

<p>
    [ <a href="reset.php">Reset Password</a> ]
</p>

<script>
    $('form').validate({ rules, messages });
</script>

<?php
include '../_footer.php';