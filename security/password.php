<?php
include '../_base.php';
$p->title = 'Update Password';

$p->auth();


$password = $new_password = $confirm = '';

$v->rules = [
    'password' => [
        'required' => true,
    ],
    'new_password' => [
        'required' => true,
        'rangelength' => [5, 20],
    ],
    'confirm' => [
        'required' => true,
        'equalTo' => 'new_password'
    ],
];

// TODO: custom message
$v->messages = [
    'confirm' => ['equalTo' => 'Value must same as new password'],
];

if ($p->post) {
    $password     = $p->req('password') . 'FNB';
    $new_password = $p->req('new_password') . 'FNB';
    $confirm      = $p->req('confirm') . 'FNB';

    $v->validate();

    if ($v->valid()) {
        // TODO: match password
        $stm = $db->prepare('SELECT COUNT(*) FROM user WHERE username = ? AND password = SHA1(?)');
        $stm->execute([$p->user, $password]);
        $count = $stm->fetchColumn();

        if ($count) {
            // TODO: update member or admin
            $table = $p->role;
            if ($table == 'admin') $table = 'staff';
            $stm = $db->prepare("UPDATE $table SET password = SHA1(?) WHERE username = ?");
            $stm->execute([$new_password, $p->user]);
        
            $p->temp('info', 'Password updated');
            $p->redirect('password.php'); // TODO: update
        }
        else {
            $v->errors['password'] = 'Not matched';
        }
    }
}

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<p class="info"><?= $p->temp('info') ?></p>

<form class="form" method="post">
    <?= $h->label('password') ?>
    <?= $h->password('password', null, 20, 'autofocus') ?>
    <?= $v->error('password') ?>

    <?= $h->label('new_password') ?>
    <?= $h->password('new_password', null, 20) ?>
    <?= $v->error('new_password') ?>

    <?= $h->label('confirm') ?>
    <?= $h->password('confirm', null, 20) ?>
    <?= $v->error('confirm') ?>

    <div>
        <button>Update</button>
        <button type="reset">Reset</button>
    </div>
</form>

<script>
    $('form').validate({ rules, messages });
</script>

<?php
include '../_footer.php';