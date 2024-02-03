<?php
require_once '../model/blog.php';
require_once '../model/config.php';

// Start the session
session_start();

// Check if the session is empty
if (!isset($_SESSION['user_id'])) {
    // Redirect to index.php
    header("Location: ../my_blog.php");
    exit(); // Make sure to exit after the header to prevent further execution
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Instantiate the Post class
    $post = new Post(new Database());

    // Check if the post belongs to the current user before deleting
    if ($post->doesPostExist($postId)) {
        // Perform the deletion only if the post exists
        // Add additional logic here if needed

        // Delete the post
        $result = $post->deletePost($postId);

        // Set session status based on deletion result
        $_SESSION['Status'] = $result ? 'Post deleted successfully!' : 'Failed to delete post.';

        // Redirect back to the page displaying the posts
        header("Location: ../my_blog.php");
        exit();
    } else {
        // If the post doesn't exist, redirect to my_posts.php with an error status
        $_SESSION['Status'] = 'Post not found.';
        header("Location: ../my_blog.php");
        exit();
    }
} else {
    // If 'id' parameter is not set, redirect to my_posts.php with an error status
    $_SESSION['Status'] = 'Invalid request to delete post.';
    header("Location: ../my_blog.php");
    exit();
}
?>
