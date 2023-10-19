<?php
ob_start(); // Start output buffering

$dbname = "khoj";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

try {
    $con = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->exec("SET NAMES utf8");
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
