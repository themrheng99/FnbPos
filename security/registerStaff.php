<?php
include '../_base.php';
$p->title = 'Register a staff';

//$p->auth('Admin');

$username = $password = $confirm = $email = '';

$v->rules = [
    'username' => [
        'required'  => true,
        'maxlength' => 10,
        'dbUnique'  => ['user', 'username'],       // server-only
        'remote'    => '../_remote.php?fn=username', // client-only
    ],
    'password' => [
        'required'    => true,
        'rangelength' => [5, 20],
    ],
    'confirm' => [
        'required' => true,
        'equalTo'  => 'password',
    ],
    'email' => [
        'required'  => true,
        'maxlength' => 100,
        'email'     => true,
        'dbUnique' => ['user','email'], // server-only
        'remote'   => '../_remote.php?fn=email',// client-only
    ],
    'file' => [
        'required'  => true,
        'maxsize'   => 1 * 1024 * 1024, // 1MB
        'accept'    => 'image/jpeg,image/png',
        'extension' => 'jpg,jpeg,png',
    ],
];

// TODO: custom message
$v->messages = [
    'username' => ['required' => 'must different from old one'],
    'confirm' => ['equalTo' => 'Value must same as password'],
    'email' => ['required' => 'must different from old one'],
];

if ($p->post) {
    $username= $p->req('username');
    $password= $p->req('password') . 'FNB';
    $confirm = $p->req('confirm') . 'FNB';
    $email   = $p->req('email');
    $file    = $p->file('file');

    $username = strtoupper($username);
    $email = strtoupper($email);

    $v->validate();

    if ($v->valid()) {
        try {
            if ($file) {
                $photo = uniqid() . '.jpg';
                include '../lib/SimpleImage.php';
                $img = new SimpleImage();
                $img->fromFile($file->tmp_name)
                    ->thumbnail(200, 200)
                    ->toFile("../photo/$photo", 'image/jpeg');
    
                // get unique id for old image, and delete it from uploaded file folder
                $stmt = $db->prepare('SELECT photo FROM user WHERE id = ?');
                $stmt->execute([$id]);
                $image = $stmt->fetchColumn();
                unlink("../photo/".$image);
            }
        } catch (Exception $e) {
            // get back old image unique id
            $photo = $photo;
        }

        // TODO: insert member
        $stm = $db->prepare('INSERT INTO staff (username, password, email, status, role, photo)
                            VALUES (?, SHA1(?), ?, 1, "staff", ?)');
        $stm->execute([$username, $password, $email, $photo]);

        $p->temp('info', "<b>$username</b> register successfully");
        $p->redirect('../maintenance/staffTable.php');
    }
}

// output -----------------------------------------------------------------------------------------
include '../_header.php';
?>

<p class="info"><?= $p->temp('info') ?></p>

<form class="form" method="post" enctype="multipart/form-data">
    <?= $h->label('username') ?>
    <?= $h->text('username', null, 50, 'autofocus data-upper') ?>
    <?= $v->error('username') ?>

    <?= $h->label('password') ?>
    <?= $h->password('password', null, 100) ?>
    <?= $v->error('password') ?>

    <?= $h->label('confirm') ?>
    <?= $h->password('confirm', null, 100) ?>
    <?= $v->error('confirm') ?>

    <?= $h->label('email') ?>
    <?= $h->text('email', null, 50, 'data-upper') ?>
    <?= $v->error('email') ?>

    <?= $h->label('photo') ?>
    <div>
        <small>Select Image to Replace</small>
        <label data-preview>
            <?= $h->file('file', 'image/jpeg,image/png') ?>
            <img src="/image/photo.png">
        </label>
    </div>
    <?= $v->error('file') ?>

    <div>
        <button>Register</button>
        <button type="reset">Reset</button>
        <button data-get='../role/selectRole.php'>Cancel</button>
    </div>
</form>

<!-- TODO: enable client-side validation -->
<script>
    $('form').validate({ rules, messages, ignore: '' });

    $('#preview input').on('change', function (e) {
        let img = $('#preview img')[0];

        if ($(this).valid()) {
            let f = this.files[0];
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = '../image/upload.png';
        }
    });
</script>

<?php
include '../_footer.php';