<?php
session_start();
include '../database/db.php';


$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $email_check_sql = "SELECT * FROM admin WHERE admin_email = '$email'";
    $email_check_result = mysqli_query($conn, $email_check_sql);

    if (mysqli_num_rows($email_check_result) === 0) {
        $error_message = "User does not exist!";
    } else {
        $user = mysqli_fetch_assoc($email_check_result);
        if ($user['admin_password'] === $password) {
            $_SESSION['admin_email'] = $user['admin_email'];
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
    <link rel="icon" href="../images/bsitlogo.png">
    <link rel="stylesheet" href="../css/adminlogin.css">
</head>
<body>
    <section id="formContainer" class="<?= !empty(trim($error_message)) ? 'show' : '' ?>">
        <div class="form_container">
            <div class="logo_container">
                <img src="../images/bsitlogo.png" alt="Logo">
            </div>
            <div class="form login_form">
                <form action="" method="post">
                    <div class="type">
                        <h3>Welcome Admin</h3>
                    </div>
                    <?php if (!empty($error_message)) : ?>
                        <p id="errorMessage" style="color: red;"><?= $error_message; ?></p>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000); 
            }
        });
    </script>
</body>
</html>
