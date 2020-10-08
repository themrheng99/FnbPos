<?php
include '../_base.php';
$p->title = 'Edit Item';

$p->auth('admin');

$name = $description = $price = $image = '';

$v->rules = [
    'name' => ['required' => true, 'maxlength' => 100],
    'description' => ['maxlength' => 1000],
    'price' => ['required' => true, 'maxlength' => 10],
    'image' => [
        'maxsize'   => 1 * 1024 * 1024,
        'accept' => 'image/png, image/jpeg',
        'extension' => 'png, jpg, jpeg',
    ],
];

$id = $p->req('id');

// check if id exist
$stmt = $db->prepare('SELECT * FROM item WHERE id = ?');
$stmt->execute([$id]);
$i = $stmt->fetch();
if (!$i) $p->redirect('menu-manage.php');

$name = $i->name;
$description = $i->description;
$price = $i->price;
$image = $i->image;

if ($p->post) {
    $name = $p->req('name');
    $description = $p->req('description');
    $price = $p->req('price');
    $image = $p->file('image');

    // delete validation for image if no new image uploaded
    if (!$image) {
        $image = $i->image;
        $v->rules = [
            'image' => [],
        ];
    }

    // remove html tags
    $name = strtoupper($name);
    $name = strip_attr($name);
    $description = strtoupper($description);
    $description = strip_attr($description);

    $v->validate();

    if ($v->valid()) {
        try {
            $filename = uniqid() . '.jpg';
            include '../lib/SimpleImage.php';
            $img = new SimpleImage();
            $img->fromFile($image->tmp_name)
                ->thumbnail(200, 200)
                ->toFile("../menu-image/$filename", 'image/jpeg');

            // get unique id for old image, and delete it from uploaded file folder
            $stmt = $db->prepare('SELECT image FROM item WHERE id = ?');
            $stmt->execute([$id]);
            $image = $stmt->fetchColumn();
            unlink("../menu-image/".$image);
        } catch (Exception $e) {
            // get back old image unique id
            $filename = $image;
        }

        $stmt = $db->prepare('UPDATE item SET name = ?, description = ?, price = ?, image = ? WHERE id = ?');
        $stmt->execute([$name, $description, $price, $filename, $id]);

        $p->temp('info', "Item <b>$id</b> updated");
        $p->redirect('menu-manage.php');
    }
}

include '../_header.php';
?>

<a href="menu-manage.php" class="btn btn-outline-info">Back</a><br></br>

<form method="post" enctype="multipart/form-data" class="form">
    <?= $h->label('name') ?>
    <?= $h->text('name', $name, 100, 'data-upper') ?>
    <?= $v->error('name') ?>

    <?= $h->label('description') ?>
    <?= $h->text('description', $description, 1000, 'data-upper') ?>
    <?= $v->error('description') ?>

    <?= $h->label('price') ?>
    <?= $h->number('price', $price, 0, 99, .01) ?>
    <?= $v->error('price') ?>

    <?= $h->label('image') ?>
    <div>
        <label id="preview">
            <?= $h->file('image', 'image/png, image/jpeg') ?>
            <img src="../menu-image/<?= $image ?>">
        </label>
    </div>
    <?= $v->error('image') ?>

    <div>
        <button class="btn btn-info">Update</button>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button data-get='menu-manage.php' class="btn btn-danger">Cancel</button>
    </div>
</form>

<!-- TODO: enable client-side validation -->
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