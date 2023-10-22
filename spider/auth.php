<?php
session_start();

$valid_username = "Ram";
$valid_password = "Ram@9912";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid username or password. Please try again.";
    }
}
?>
