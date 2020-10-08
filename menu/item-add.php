<?php
include '../_base.php';
$p->title = 'Insert Item';

$p->auth('admin');

$id = $name = $description = $price = $category = $image = '';

$v->rules = [
    'name' => ['required' => true, 'maxlength' => 100],
    'description' => ['maxlength' => 1000],
    'price' => ['required' => true, 'maxlength' => 10],
    'category' => ['required' => true, 'exist' => array_keys($CATEGORIES)],
    'image' => [
        'required' => true,
        'maxsize'   => 1 * 1024 * 1024,
        'accept' => 'image/png, image/jpeg',
        'extension' => 'png, jpg, jpeg',
    ],
];

if ($p->post) {
    $name    = $p->req('name');
    $price   = $p->req('price');
    $description = $p->req('description');
    $category = $p->req('category');
    $image = $p->file('image');

    // strip_attr - rejecting html tags
    $name = strtoupper($name);
    $name = strip_attr($name);
    $description = strtoupper($description);
    $description = strip_attr($description);

    $v->validate();

    if ($v->valid()) {
        // auto generate id
        $max = $db->query("SELECT MAX(id) FROM item WHERE category = '$category'")->fetchColumn();
        $next = substr($max, 1) + 1;
        $id = sprintf($category . '%03d', $next);

        // create unique id for image incase name is duplicated
        $filename = uniqid() . '.jpg';

        include '../lib/SimpleImage.php';
        $img = new SimpleImage();
        $img->fromFile($image->tmp_name)
            ->thumbnail(200, 200)
            ->toFile("../menu-image/$filename", 'image/jpeg');

        $stmt = $db->prepare('
            INSERT INTO item (id, name, description, price, category, image)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$id, $name, $description, $price, $category, $filename]);

        $p->temp('info', "Item <b>$id</b> inserted");
        $p->redirect('menu-manage.php');
    }
}

include '../_header.php';
?>

<a href="menu-manage.php" class="btn btn-outline-info">Back</a><br></br>

<form method="post" enctype="multipart/form-data" class="form">
    <?= $h->label('category') ?>
    <?= $h->select('category', null, $CATEGORIES, 'autofocus') ?>
    <?= $v->error('category') ?>

    <?= $h->label('name') ?>
    <?= $h->text('name', null, 20, 'data-upper') ?>
    <?= $v->error('name') ?>

    <?= $h->label('description') ?>
    <?= $h->text('description', null, 1000, 'data-upper') ?>
    <?= $v->error('description') ?>

    <?= $h->label('price') ?>
    <?= $h->number('price', 0.00, 0, 99, .01) ?>
    <?= $v->error('price') ?>

    <?= $h->label('image') ?>
    <div>
        <label id="preview">
            <?= $h->file('image', 'image/png, image/jpeg') ?>
            <img src="../image/upload.png">
        </label>
    </div>
    <?= $v->error('image') ?>

    <div>
        <button class="btn btn-info">Submit</button>
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

    // display upload image as preview
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
