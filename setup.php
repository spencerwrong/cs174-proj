<?php // setupUsers.php
require_once('login.php');
$conn = new mysqli($hn,$un,$pw,$db);
if($conn->connect_error) die($conn->connect_error);

$query = "CREATE DATABASE IF NOT EXISTS viruscheck";
$result = $conn->query($query);
if(!$result) die ($conn->error);

$query = "CREATE TABLE IF NOT EXISTS users (fname VARCHAR(32), lname VARCHAR(32), username VARCHAR(32) PRIMARY KEY, password VARCHAR(32), admin TINYINT(1))";
$result = $conn->query($query);
if(!$result) die ($conn->error);

$query = "CREATE TABLE IF NOT EXISTS viruses (name VARCHAR(32), seq VARCHAR(20))";
$result = $conn->query($query);
if(!$result) die($conn->error);

$salt1 = "Xho3ENU3";
$salt2 = "08ao71IE";

$fname = "Spencer";
$lname = "Wong";
$user = "spencer1";
$pass = "1password1";
$token = hash('ripemd128', "$salt1$pass$salt2");

add_user($conn, $fname, $lname, $user, $token, 1);

$fname = "Natisha";
$lname = "Khadgi";
$user = "natisha2";
$pass = "2password2";
$token = hash('ripemd128', "$salt1$pass$salt2");

add_user($conn, $fname, $lname, $user, $token, 0);

function add_user($connection, $fn, $ln, $un, $pass, $admin) {
    $query = "INSERT INTO users VALUES('$fn', '$ln', '$un', '$pass', '$admin')";
    $result = $connection->query($query);
    if(!$result) die($connection->error);
}

echo "done";
