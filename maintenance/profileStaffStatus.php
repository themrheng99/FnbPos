<?php
include '../_base.php';
$p->title = 'Update Staff Status by All Admin';

//$p->auth('admin');

$id = $username = $email = $status = $photo = '';

// select member
if($p->get) {
    $id = $p->req('id');

    $stm = $db->prepare('SELECT * FROM staff WHERE id = ?');
    $stm->execute([$id]);
    $m = $stm->fetch();

    $id = $m->id;
    $username = $m->username;
    $email = $m->email;
    $status = $m->status;
    $photo = $m->photo;
}

$v->rules = [
    'status' => [
        'required' => true,
        'exist' => array_keys($STATUS),
    ],
];

if ($p->post) {
    $id = $p->req('id');
    $status  = $p->req('status');

    $username = strtoupper($username);
    $email = strtoupper($email);

    $v->validate();

    if ($v->valid()) {
        // update user
        $stm = $db->prepare('UPDATE staff SET `status` = ?
                             WHERE id = ?');
        $stm->execute([$status, $id]);

        $p->temp('info', 'Profile updated');
        $p->redirect('staffTable.php');
    }
}

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<p class="info"><?= $p->temp('info') ?></p>

<form class="form" method="post" enctype="multipart/form-data">
    
    <?= $h->label('Id') ?>
    <b><?= $id ?></b>
    
    <?= $h->label('username') ?>
    <b><?= $username ?></b>

    <?= $h->label('email') ?>
    <b><?= $email ?></b>

    <?= $h->label('Status') ?>
    <?= $h->radios('status', null, $STATUS, False) ?>
    <?= $v->error('status') ?>

    <?= $h->label('photo') ?>
    <div>
        <label data-preview>
            <b><img src="../photo/<?= $photo ?>"></b>
        </label>
    </div>

    <div>
        <button>Update</button>
        <button type="reset">Reset</button>
        <button data-get='staffTable.php'>Cancel</button>
    </div>
</form>

<script>
    $('form').validate({ rules, messages, ignore: '' });
</script>

<?php
include '../_footer.php';