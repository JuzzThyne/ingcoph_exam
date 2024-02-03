<?php
// Check for login error in the session
if (isset($_SESSION['login_error'])) {
    $errorMessage = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear the session variable
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form action="./controller/login.php" method="post">
                <div class="form-group">
                    <label for="loginUsername">Username:</label>
                    <input type="text" class="form-control" id="loginUsername" name="loginUsername" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required autocomplete="current-password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="showPasswordToggle">
                                <i class="fa fa-eye" aria-hidden="true" id="showPasswordIcon"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-block">SignIn</button>
            </form>
        </div>
    </div>
</div>

<!-- Add Font Awesome library for the eye icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    // JavaScript to toggle password visibility
    const passwordInput = document.getElementById('loginPassword');
    const showPasswordIcon = document.getElementById('showPasswordIcon');

    showPasswordIcon.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPasswordIcon.classList.remove('fa-eye');
            showPasswordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            showPasswordIcon.classList.remove('fa-eye-slash');
            showPasswordIcon.classList.add('fa-eye');
        }
    });
</script>
