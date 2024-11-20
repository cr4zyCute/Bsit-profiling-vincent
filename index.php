<?php
session_start();
include 'database/db.php';

$error_message = ""; // Initialize error message to empty

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user exists
    $email_check_sql = "SELECT * FROM loginCredentials WHERE email = '$email'";
    $email_check_result = mysqli_query($conn, $email_check_sql);

    if (mysqli_num_rows($email_check_result) === 0) {
        $error_message = "User does not exist!";
    } else {
        $user = mysqli_fetch_assoc($email_check_result);

        // Check for correct password
        if ($user['password'] === $password) {
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the student dashboard or desired page
            header('Location: student.php');
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
    <title>Document</title>
    <link rel="icon" href="./images/bsitlogo.png">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" rel="stylesheet">

</head>
<body>
<header>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <a href="#" class="logo">
            <img src="images/logo.jpg" alt="logo">
            <h2>BSIT</h2>
        </a>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About us</a></li>
            <li><a href="#">Contact us</a></li>
        </ul>
        <button class="login-btn" onclick="toggleLoginForm()">LOG IN</button>
    </nav>
</header>

<section id="home">
    <img src="./images/IMG_3564.jpg" alt="">
<section id="formContainer" class="<?= !empty(trim($error_message)) ? 'show' : '' ?>">
    <div class="form_container">
        <i class="uil uil-times form_close" onclick="toggleLoginForm()"></i>
        <div class="form login_form">
            <form action="" method="post">
                <h2>Login</h2>
                <?php if (!empty($error_message)) : ?>
                    <p style="color: red;"><?= $error_message; ?></p>
                <?php endif; ?>
                <div class="input_box">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <i class="uil uil-envelope-alt email"></i>
                </div>
                <div class="input_box">
                    <input type="password" name="password" placeholder="Enter your password" required>
                    <i class="uil uil-lock password"></i>
                </div>
                <div class="option_field">
                    <span class="checkbox">
                        <input type="checkbox" id="check">
                        <label for="check">Remember me</label>
                    </span>
                </div>
                <button type="submit" name="login" class="button">Login Now</button>
                <div class="login_signup">Don't have an account?
                    <div>
                        <a href="studentRegistrationForm.php" id="signup" style="z-index: 1;">Signup</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

</section>

    <section>
        <div id="about" class="container reveal">
            <h1>BSIT</h1>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container reveal">
            <h1>BSIT</h1>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container reveal">
            <h1>BSIT</h1>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
            <div class="cards">
                <div class="text-card">
                    <h3>BSIT</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt </p>
                </div>
            </div>
        </div>
    </section>

 
    <footer>
        <p>&copy; 2024 BSIT. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </div>
    </footer>
<script src="./js/index.js" ></script>
<script type="text/javascript">
    window.addEventListener('scroll', reveal);
function reveal(){
    var reveals = document.querySelectorAll('.reveal')
     
    for(var i = 0; i < reveals.length; i++){
    var windowheight = window.innerHeight;
    var revealtop = reveals[i].getBoundingClientRect().top;
    var revealpoint = 150;

    if(revealtop < windowheight - revealpoint){
        reveals[i].classList.add('active');
    }else{
        reveals[i].classList.remove('active');
        }
    }
}
</script>
</body>
</html>