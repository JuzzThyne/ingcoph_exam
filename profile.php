<?php
    require_once './model/User.php'; // Assuming your User class is in a file named User.php
    require_once './model/Config.php';

    // Start the session
    session_start();

    // Check if the session is empty
    if (!isset($_SESSION['user_id'])) {
        // Redirect to index.php
        header("Location: index.php");
        exit(); // Make sure to exit after the header to prevent further execution
    }

    // Instantiate the User class with the database connection
    $user = new User(new Database()); // You need to replace 'Database' with your actual database class

    // Example usage of getUser method
    $userId = $_SESSION['user_id'];
    $userInfo = $user->getUser($userId);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newUsername = $_POST["new_username"];
        $newPassword = $_POST["new_password"];

        // Validate inputs and update user information
        if (!empty($newUsername) && !empty($newPassword)) {
            if ($user->updateUser($userId, $newUsername, $newPassword)) {
                $_SESSION['Status'] = 'Updated Successfully!';
            } else {
                 $_SESSION['Status'] = "Failed to update user information.";
            }
        } else {
            $_SESSION['Status'] = "Please fill out both username and password fields.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="mb-3 d-flex justify-content-end">
            <a href="index.php" class="btn btn-success">Home</a>
        </div>
        <h1>Hello <?= $userInfo['Username']; ?></h1>
        <?php
                // Display registration status if available
                if (isset($_SESSION['Status'])) {
                    echo '<div class="alert alert';
                    echo $_SESSION['Status'] === 'Updated Successfully!' ? ' alert-success' : ' alert-danger';
                    echo ' text-center">' . $_SESSION['Status'] . '</div>';

                    // Clear the registration status from the session
                    unset($_SESSION['Status']);
                }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="new_username">New Username:</label>
                <input type="text" class="form-control" id="new_username" name="new_username" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Information</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and jQuery script links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
