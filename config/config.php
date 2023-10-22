<?php
ob_start(); // Start output buffering

$dbname = "khoj";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

try {
    $con = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass); // Corrected the PDO DSN
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

header('Content-Type: text/html; charset=utf-8');
?>
