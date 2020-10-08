<?php
include '../_base.php';
$p->title = 'Update Profile by admin itself';

//$p->auth('admin');

$id = $username = $email = $photo = '';

// select staff data and show in the profile
if($p->get){
    $stm = $db->prepare('SELECT id, username, email, photo FROM staff WHERE username = ?');
    $stm->execute([$p->user]);
    $m = $stm->fetch();

    $id = $m->id;
    $username = $m->username;
    $email = $m->email;
    $photo = $m->photo;
}

$v->rules = [
    'username' => [
        'required'  => true,
        'maxlength' => 100,
        'dbUnique' => ['user','username'], // server-only
        'remote'   => '../_remote.php?fn=username',// client-only
    ],
    'email' => [
        'required'  => true,
        'maxlength' => 100,
        'email'     => true,
        'dbUnique' => ['user','email'], // server-only
        'remote'   => '../_remote.php?fn=email',// client-only
    ],
    'file' => [
        // NOTE: file is not required here
        'maxsize'   => 1 * 1024 * 1024,  
        'accept'    => 'image/jpeg,image/png',
        'extension' => 'jpg,jpeg,png',
    ],
];

$v->messages = [
    'username' => ['required' => 'must different from old one'],
    'email' => ['required' => 'must different from old one'],
];

if ($p->post) {
    $id = $p->req('id');
    $username = $p->req('username');
    $email = $p->req('email');
    $file  = $p->file('file');

    $username = strtoupper($username);
    $email = strtoupper($email);

    if (!$file) {
        $photo = $m->photo;
        $v->rules = ['file' => ''];
    }
    $v->validate();
    //var_dump($v->valid());

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

        // update member
        $stm = $db->prepare('UPDATE staff SET username = ?, email = ?, photo = ?
                             WHERE username = ?');
        $stm->execute([$username, $email, $photo, $p->user]);

        $p->temp('info', 'Profile updated');
        $p->redirect('adminTable.php');
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
    <?= $h->text('username', null, 100, 'autofocus data-upper') ?>
    <?= $v->error('username') ?>

    <?= $h->label('email') ?>
    <?= $h->text('email', null, 100, 'data-upper') ?>
    <?= $v->error('email') ?>

    <?= $h->label('photo') ?>
    <div>
        <small>Select Image to Replace</small>
        <label data-preview>
            <?= $h->file('file', 'image/jpeg,image/png') ?>
            <img src="../photo/<?= $photo ?>">
        </label>
    </div>
    <?= $v->error('file') ?>

    <div>
        <button>Update</button>
        <button type="reset">Reset</button>
        <button data-get='adminTable.php'>Cancel</button>
    </div>
</form>

<script>
    $('form').validate({
        rules,
        messages,
        ignore: ''
    });

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