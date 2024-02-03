<?php
    require_once './model/blog.php';
    require_once './model/config.php';
    // Start the session
    session_start();

    // Check if the session is empty
    if (!isset($_SESSION['user_id'])) {
        // Redirect to index.php
        header("Location: index.php");
        exit(); // Make sure to exit after the header to prevent further execution
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .custom-table tbody tr:nth-child(odd) {
        background-color: #f2f2f2; /* Light gray background for odd rows */
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #d9d4d4; /* White background for even rows */
    }
</style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="mb-3 d-flex justify-content-end">
            <a href="index.php" class="btn btn-success">Home</a>
        </div>
        <h1 class="mb-4 text-center">My Blog Posts</h1>
        <?php
                // Display registration status if available
                if (isset($_SESSION['Status'])) {
                    echo '<div class="alert alert';
                    echo $_SESSION['Status'] === 'Updated Successfully!' ? ' alert-success' : ' alert-danger';
                    echo $_SESSION['Status'] === 'Post deleted successfully!' ? ' alert-success' : ' alert-danger';
                    echo ' text-center">' . $_SESSION['Status'] . '</div>';

                    // Clear the registration status from the session
                    unset($_SESSION['Status']);
                }
                ?>
        <?php
        $post = new Post(new Database());
        $userId = $_SESSION['user_id'];
        $posts = $post->getMyPosts($userId);

        // Display blog posts and comments in a table
        if (empty($posts)) {
            echo "<p class='text-center'>No blog posts yet.</p>";
        } else {
            echo "<table class='table table-striped custom-table text-center'>";
            echo "<thead class='thead-dark'><tr><th>Title</th><th>Content</th><th>Image</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            foreach ($posts as $post) {
                echo "<tr>";
                echo "<td>" . $post['Title'] . "</td>";
                echo "<td>" . $post['Content'] . "</td>";

                // Check if the post has an image
                echo "<td>";
                if (!empty($post['image_path'])) {
                    $imagePath = $post['image_path'];
                    echo "<img src='./uploads/$imagePath' alt='Post Image' class='img-fluid' style='max-width: 100px; max-height: 100px;'>";
                }
                echo "</td>";

                // Edit and Delete buttons
                echo "<td class='align-middle text-center'>";
                echo "<a href='edit_post.php?id=" . $post['PostID'] . "' class='btn btn-warning m-2 btn-sm'>Edit</a> ";
                echo "<a href='./controller/delete_post.php?id=" . $post['PostID'] . "' class='btn m-2 btn-danger btn-sm'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        ?>
    </div>

    <!-- Bootstrap JS and Popper.js links (required for Bootstrap functionality) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
