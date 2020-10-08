<?php
include '_base.php';

$fn = $p->req('fn');

// function student_id() {
//     global $p, $v;
    
//     $id = $p->req('id');
//     $valid = $v->dbUnique($id, 'student', 'id'); //true or false
//     return $valid? 'true' : 'Value not unique';
// }

function username() {
    global $p, $v;
    $username = $p->req('username');
    $valid = $v->dbUnique($username, 'user', 'username');
    return $valid ? 'true' : 'Value not unique';
}

function email() {
    global $p, $v;
    $email = $p->req('email');
    $valid = $v->dbUnique($email, 'user', 'email');
    return $valid ? 'true' : 'Value not unique';
}

//output----------------------------------------------------------------------------
//valid --> json string 'true'
//invalid --> other
$result = $fn();
echo json_encode($result);