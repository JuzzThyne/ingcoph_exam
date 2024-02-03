<?php
session_start(); // Start the session

require_once '../model/config.php'; // Assuming you have a Database class
require_once '../model/user.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    // Validate input (you should add more validation)
    if (!empty($username) && !empty($password)) {
        $db = new Database(); // Create a Database instance
        $user = new User($db); // Create a User instance

        // Attempt to login the user
        if ($user->login($username, $password)) {
            // Redirect to index.php on successful login
            header('Location: ../index.php');
            exit();
        } else {
            // Login failed, store error message in session and redirect
            $_SESSION['Status'] = 'Invalid username or password';
            header('Location: ../index.php');
            exit();
        }
    } else {
        // Missing fields, store error message in session and redirect
        $_SESSION['Status'] = 'Both username and password are required';
        header('Location: ../index.php');
        exit();
    }
}
?>
