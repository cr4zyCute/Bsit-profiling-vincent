<?php
session_start();
include '../database/db.php';

$error_message = ""; // Initialize error message

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user exists
    $email_check_sql = "SELECT * FROM admin WHERE admin_email = '$email'";
    $email_check_result = mysqli_query($conn, $email_check_sql);

    if (mysqli_num_rows($email_check_result) === 0) {
        $error_message = "User does not exist!";
    } else {
        $user = mysqli_fetch_assoc($email_check_result);

        // Verify the password
        if ($user['admin_password'] === $password) {
            $_SESSION['admin_email'] = $user['admin_email'];

            // Redirect to the admin page
            header('Location: admin.php');
            exit();
        } else {
            $error_message = "Wrong password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h1>Welcome Admin</h1>
    <section id="formContainer" class="<?= !empty(trim($error_message)) ? 'show' : '' ?>">
        <div class="form_container">
            <div class="form login_form">
                <form action="" method="post">
                    <h2>Login</h2>
                    <?php if (!empty($error_message)) : ?>
                        <p style="color: red;"><?= $error_message; ?></p>
                    <?php endif; ?>
                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" name="login" class="button">Login Now</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
