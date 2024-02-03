<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add this line inside the <head> section of your HTML file -->
    <link rel="stylesheet" type="text/css" href="blog.css">
</head>
<body>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Create Blog Post</h2>
        </div>
        <div class="card-body">
            <!-- Form -->
            <form action="./controller/create_post.php" method="post" enctype="multipart/form-data">
                <!-- Title -->
                <div class="form-group">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                </div>

                <!-- Content -->
                <div class="form-group">
                    <textarea class="form-control" id="content" name="content" rows="4" placeholder="Content" required></textarea>
                </div>

                <!-- Image -->
                <div class="form-group">
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">Create Post</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and jQuery (optional) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
