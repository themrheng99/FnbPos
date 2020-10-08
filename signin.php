<?php 
include '_base.php';

$p->title = 'Login';

$t = $p->get('table') ?? '';

$stmt = $db->prepare('SELECT * FROM `table` WHERE id = ?');
$stmt->execute([$t]);
$t = $stmt->fetch();

if (!$t) $p->redirect('tablenotfound.html');
if ($p->user) $p->redirect('home.php');

$_SESSION['table'] = $t ?? null;

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $p->title ?></title>
    <link rel="shortcut icon" href="/image/favicon.png">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery.validate.js"></script>
    <script src="/js/additional-methods.js"></script>
    <script src="/js/app.js"></script>
    <script>
        // initialize client-side validation rules and messages
        $.extend($.validator.messages, <?= json_encode($v->defaults) ?>);
        let rules = processRules(<?= json_encode($v->rules) ?>);
        let messages = <?= json_encode($v->messages) ?>;
    </script>
    <?= $p->head ?>
</head>

<body>
    <header>
        <h1 class="header">Restaurant Name</h1>
    </header>
    <main>
<?php

if ($t->status) include ('table/tableinuse.html');
else {
    $str = "
    <h1><?= $p->title ?></h1>
    <div class='container'>
        <button data-get='/security/login.php?u=guest' class='btn btn-info'>GUEST</button>
        <button data-get='/security/login.php?u=member' class='btn btn-info'>MEMBER</button>

        <a href='/signup.php'>Register as member?</a>
    </div>
    ";
    echo $str;
}
?>

<?php include '_footer.php';