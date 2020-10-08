<?php

// general ----------------------------------------------------------------------------------------
ob_start(); // start output buffering
session_start(['cookie_httponly' => true]); // start session
date_default_timezone_set('Asia/Kuala_Lumpur');

$db = new PDO('mysql:host=localhost;port=3306;dbname=db','root','', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false
]);

$BASE_URL = $_SERVER['HTTP_HOST'];  

$TAX = 0.1; // use in Bill.

// lookup tables ----------------------------------------------------------------------------------
$CATEGORIES = $db->query('SELECT id, name FROM category')
                 ->fetchAll(PDO::FETCH_KEY_PAIR);

$TABLETYPE = $db->query('SELECT id, type FROM tabletype')
                ->fetchAll(PDO::FETCH_KEY_PAIR);

$STATUS_TABLE = [
    '0' => 'AVAILABLE',
    '1' => 'IN-USE',
];

$STATUS_ORDER = [
    '0' => 'PENDING CONFIRMATION',
    '1' => 'CONFIRMED',
    '2' => 'PREPARING',
    '3' => 'SERVED',
];

$STATUS_BILL = [
    '0' => 'UNPAID',
    '1' => 'PAID',
];

$STAFF_ROLE = [
    'admin', 'staff'
];

$STATUS = [
    '1' => 'Active',
    '0' => 'Inactive'
];


// classes ----------------------------------------------------------------------------------------
include 'lib/Page.php';
include 'lib/Html.php';
include 'lib/Validator.php';

$p = new Page();
$h = new Html();
$v = new Validator();

function strip_attr($input) {
    return preg_replace('/<([a-z][a-z0-9]*)[^>]*>/i', '<$1>', $input);;
}