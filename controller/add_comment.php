<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['post_id'], $_POST['comment'])) {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];
        $commentText = $_POST['comment'];
        $name = $_SESSION['fullname'];

        // Include necessary files and initialize Comment class
        require_once '../model/blog.php';
        require_once '../model/Config.php';

        $comment = new Comment(new Database());

        // Add the comment
        if ($comment->addComment($postId, $userId, $commentText, $name)) {
            // Redirect to the blog page or wherever you want
            header("Location: ../index.php");
            exit();
        } else {
            // Handle error, maybe display an error message
            echo "Failed to add comment.";
        }
    }
}
?>
