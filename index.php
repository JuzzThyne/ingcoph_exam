<?php
require_once './model/blog.php';
require_once './model/config.php';

// Start the session
session_start();
// Output session information for debugging
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Blog</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add this line inside the <head> section of your HTML file -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="./images/zohonotebook_logo.png" alt="Your Logo" width="30" height="30" class="d-inline-block align-top">
            Notebook Blog
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '  <li class="nav-item p-2">
                                <a class="nav-link btn btn-primary text-white rounded-sm" href="my_blog.php">My Post</a>
                            </li>
                            <li class="nav-item p-2">
                                <a class="nav-link btn btn-primary text-white rounded-sm" href="profile.php">Profile</a>
                            </li>
                            <li class="nav-item p-2">
                            <form method="post" action="./controller/logout.php" class="">
                                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                            </form>
                          </li>';
                } else {
                    echo '<li class="nav-item p-2">
                            <a class="nav-link btn btn-primary text-white" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
                          </li>
                          <li class="nav-item p-2">
                            <a class="nav-link btn btn-success text-white" href="#" data-toggle="modal" data-target="#registerModal">Register</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    // Check if the user is not logged in
                    if (!isset($_SESSION['user_id'])) {
                        include 'login.php';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    // Check if the user is not logged in
                    if (!isset($_SESSION['user_id'])) {
                        include 'register.php'; // Create a separate PHP file for the registration form
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    // Check if the user is not logged in
                    if (!isset($_SESSION['user_id'])) {
                        include 'login.php';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Blog Post Modal -->
    <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <?php
                    // Check if the user is not logged in
                    if (isset($_SESSION['user_id'])) {
                        include 'create_blog_post.php';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Posts Section -->
    <div class="container mt-10" style="padding-top: 100px">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary rounded-pill p-2" data-toggle="modal" data-target="#addPostModal">Add Blog Post</button>
                        </div>';
                }
                ?>
                <?php
                // Display registration status if available
                if (isset($_SESSION['Status'])) {
                    echo '<div class="alert alert';
                    echo $_SESSION['Status'] === 'Registration successful!' ? ' alert-success' : ' alert-danger';
                    echo ' text-center">' . $_SESSION['Status'] . '</div>';

                    // Clear the registration status from the session
                    unset($_SESSION['Status']);
                }
                ?>
                <h2 class="mb-4">Latest Blog Posts</h2>
                <?php
                // Display blog posts and comments here
                $post = new Post(new Database());
                $comment = new Comment(new Database());
                $posts = $post->getAllPosts();

                if (empty($posts)) {
                    echo "<p class='d-flex justify-content-center align-items-center'>No blog posts yet.</p>";
                } else {
                    foreach ($posts as $post) {
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . $post['Title'] . "</h5>";
                        echo "<p class='card-text'>" . $post['Content'] . "</p>";

                        // Check if the post has an image
                        if (!empty($post['image_path'])) {
                            $imagePath = $post['image_path'];
                            echo "<div class='text-center'>";  // Center the content
                            echo "<img src='./uploads/$imagePath' alt='Post Image' class='img-fluid mt-3 mx-auto d-block' style='max-width: 300px; max-height: 200px;'>";
                            echo "</div>";
                        }

                        // Display comments for the current post
                        $postId = $post['PostID'];
                        $comments = $comment->getAllCommentsForPost($postId);

                        echo "<h6 class='mt-4'>Comments:</h6>";

                        if (empty($comments)) {
                            echo "<p>No comments yet.</p>";
                        } else {
                            echo "<ul class='list-unstyled'>";
                            foreach ($comments as $singleComment) {
                                $commentText = $singleComment['CommentText'];
                                $commentName = isset($singleComment['Name']) ? $singleComment['Name'] : '';
                                echo "<li>";
                                echo $commentName ? "<strong>{$commentName}:</strong> " : ''; // Display the name if available
                                echo $commentText;
                                echo "</li>";
                            }
                            echo "</ul>";
                        }

                        // Add a form to submit new comments
                        if (isset($_SESSION['user_id'])) {
                            echo "<form method='post' action='./controller/add_comment.php'>";
                            echo "<div class='form-group'>";
                            echo "<input type='hidden' name='post_id' value='$postId'>";
                            echo "<label for='comment'>Add Comment:</label>";
                            echo "<textarea class='form-control' id='comment' name='comment' rows='3'></textarea>";
                            echo "</div>";
                            echo "<button type='submit' class='btn btn-primary'>Submit Comment</button>";
                            echo "</form>";
                        }

                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>

            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
