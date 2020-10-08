<?php
include '../_base.php';
$p->title = 'Reset Password';

$username = $email = '';

$v->rules = [
    'username' => ['required' => true],
    'email'    => ['required' => true, 'email' => true],
];

if ($p->post) {
    $username = $p->req('username');
    $email    = $p->req('email');

    $v->validate();

    if ($v->valid()) {
        // match username & email
        $stm = $db->prepare('SELECT * FROM user WHERE username = ? AND email = ?');
        $stm->execute([$username, $email]);
        $user = $stm->fetch();

        if ($user) {
            // TODO: generate password
            $password = $p->random();

            // update member or admin
            $table = $user->role;
            if($table == 'admin') $table = 'staff'; //no admin table
            $stm = $db->prepare("UPDATE $table SET password = SHA1(?) WHERE username = ?");
            $stm->execute([$password, $username]);

            // TODO: send email
            $m = $p->mail();
            $m->addAddress($user->email, $user->username);
            // $m->addEmbeddedImage("../photo/$user->photo", 'photo');
            // $m->isHTML();
            $m->Subject = '🔐 Reset Password';
            $m->Body = "NEW PASSWORD : $password";
            $m->send();

            $p->temp('info', 'Password reset. Please check your email');
            $p->redirect();
        }
        else {
            $v->errors['email'] = 'Not matched';
        }
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

    <?= $h->label('email') ?>
    <?= $h->text('email', null, 100, 'data-upper') ?>
    <?= $v->error('email') ?>

    <div>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </div>
</form>

<script>
    $('form').validate({ rules, messages });
</script>

<?php
include '../_footer.php';