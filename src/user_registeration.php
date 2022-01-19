<?php
session_start();
include 'db.php';

$fullname = $_POST['fullname'];
$nric = $_POST['nric'];
$email = $_POST['email'];
$phoneno = $_POST['phoneno'];
$username = $_POST['username'];
$password = $_POST['password'];
$passwordconfirm = $_POST['passwordconfirm'];

if($fullname !== "AAA") {
    echo 'aaaa';
} else {
    echo 'vvv';
}
return true;
