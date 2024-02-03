<?php
require_once '../model/config.php';
require_once '../model/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['regUsername'];
    $password = $_POST['regPassword'];
    $fullname = $_POST['regFullname'];
    $confirmPassword = $_POST['confirmPassword']; 

    if (!empty($username) && !empty($password) && !empty($confirmPassword)) {
        // Check if the password and confirm password match
        if ($password === $confirmPassword) {
            $db = new Database();
            $user = new User($db);

            // Attempt to register the user
            if ($user->register($username, $password, $fullname)) {
                // Registration successful
                session_start();
                $_SESSION['Status'] = "Registration successful!";
            } else {
                // Registration failed
                session_start();
                $_SESSION['Status'] = "Registration failed!";
            }
            header('Location: ../index.php'); // Redirect to your HTML file
            exit();
        } else {
            // Password and Confirm Password do not match
            session_start();
            $_SESSION['Status'] = "Password and Confirm Password do not match.";
            header('Location: ../index.php'); // Redirect to your HTML file
            exit();
        }
    } else {
        // Please fill in all the fields
        session_start();
        $_SESSION['Status'] = "Please fill in all the fields.";
        header('Location: ../index.php'); // Redirect to your HTML file
        exit();
    }
}
?>
