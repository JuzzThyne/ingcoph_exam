<?php
require_once './model/blog.php';
require_once './model/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $post = new Post(new Database());
    $editedPost = $post->getPostById($postId);

    if (!$editedPost) {
        // Post not found
        header("Location: index.php");
        exit();
    }
} else {
    // No post ID provided
    header("Location: index.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and update the post data
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Fetch the existing image path
    $existingImagePath = $editedPost['image_path'];

    // Check if a new image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Validate and update the image
        $newImage = $_FILES['image'];
        
        // You may want to add more validation and sanitation here
        
        // Update the post with the new image
        $post->updatePost($editedPost['PostID'], $title, $content, $newImage);
        
        // Remove the existing image file
        if (!empty($existingImagePath) && file_exists($existingImagePath)) {
            unlink($existingImagePath);
        }
        $_SESSION['Status'] = 'Updated Successfully!';
        header("Location: my_blog.php");
        exit();
    } else {
        // No new image, update only title and content
        $post->updatePost($editedPost['PostID'], $title, $content);
        $_SESSION['Status'] = 'Updated Successfully!';
        header("Location: my_blog.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="mb-3 d-flex justify-content-end">
            <a href="my_blog.php" class="btn btn-success">Back</a>
        </div>
        <h1 class="mb-4 text-center">Edit Post</h1>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($editedPost['Title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($editedPost['Content']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">New Image:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
