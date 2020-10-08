<?php
include '_base.php';
$p->title = 'Sign out';
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

<h1>Successfully logout.</h1>

<?php include '_footer.php';