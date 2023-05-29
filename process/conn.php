<?php
session_start();

$user = "root";
$pass = "6180337";
$db = "projeto_pizzaria";
$host = "localhost";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
    $conn -> setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn -> setattribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {

print "Error: " . $e -> getMessage() . "<br/>";
die();

}



?>