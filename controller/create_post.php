<?php
require_once '../model/config.php'; // Assuming you have a Database class
require_once '../model/blog.php';

// Start the session (if not already started)
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming form fields are named title, content, and image
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']; // Assuming file input is named 'image'

    // Validate input (you should add more validation)
    if (!empty($title) && !empty($content) && !empty($image)) {
        // Check if user_id is set in the session
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $db = new Database(); // Create a Database instance
            $post = new Post($db); // Create a Post instance

            // Attempt to create the post
            if ($post->createPost($title, $content, $userId, $image)) {
                // Redirect to a success page or index.php
                header('Location: ../index.php');
                exit();
            } else {
                // Creation failed, redirect with an error message
                header('Location: ../index.php?error=1');
                exit();
            }
        } else {
            // user_id not set in session, redirect with an error message
            header('Location: ../index.php?error=3');
            exit();
        }
    } else {
        // Missing fields, redirect with an error message
        header('Location: ../index.php?error=2');
        exit();
    }
}
?>
