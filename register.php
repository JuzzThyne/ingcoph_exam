
<?php
// Check if the session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form action="./controller/register.php" method="post">
                <div class="form-group">
                    <label for="regFullname">Fullname:</label>
                    <input type="text" class="form-control" id="regFullname" name="regFullname" required>
                </div>
                <div class="form-group">
                    <label for="regUsername">Username:</label>
                    <input type="text" class="form-control" id="regUsername" name="regUsername" required>
                </div>
                <div class="form-group">
                    <label for="regPassword">Password:</label>
                    <input type="password" class="form-control" id="regPassword" name="regPassword" required autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-success btn-block">SignUp</button>
            </form>
        </div>
    </div>
</div>