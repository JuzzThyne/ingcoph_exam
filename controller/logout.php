<?php
// Start the session
session_start();

if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to the login page or any other page as needed
    header("Location: ../index.php");
    exit();
}
?>
