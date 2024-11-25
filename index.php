<?php
session_start();
include 'database/db.php';

$error_message = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $email_check_sql = "SELECT * FROM loginCredentials WHERE email = '$email'";
    $email_check_result = mysqli_query($conn, $email_check_sql);

    if (mysqli_num_rows($email_check_result) === 0) {
        $error_message = "User does not exist!";
    } else {
        $user = mysqli_fetch_assoc($email_check_result);
        if ($user['password'] === $password) {
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['email'] = $user['email'];

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
    <title>BSIT WEB PAGE</title>
    <link rel="icon" href="./images/bsitlogo.png">
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" rel="stylesheet">

</head>
<body>
<header>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <a href="#" class="logo">
            <img src="images/bsitlogo.png" alt="logo">
            <h2>BSIT</h2>
        </a>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About us</a></li>
            <li><a href="https://www.facebook.com/bsitcebutech" target="_blank">Contact us</a></li>
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
                    <p id="errorMessage" style="color: red;"><?= $error_message; ?></p>
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

<section id="about">
    <div class="intro-text">
        <h2>Explore BSIT Programs</h2>
        <p>
            Discover the wide-ranging opportunities offered by our Bachelor of Science in Information Technology program. Whether you’re passionate about innovation, technology, or academic excellence, our BSIT program equips you with the tools for success in the ever-evolving IT industry.
        </p>
    </div>
    
    <div class="container reveal">
        <div class="cards">
            <div class="text-card">
                <img src="images/bsit_image_2.jpg" alt="Description of Image" class="card-image">
                <h3>BSIT</h3>
                <p>Showcasing the Bachelor of Science in Information Technology (BSIT) program at Cebu Technological University – Nation Wide Campus. Dive into a world of innovation, technology, and opportunities that prepare you for a future-driven career in IT.</p>
            </div>
        </div>
        <div class="cards">
            <div class="text-card">
                <img src="images/bsit_image_1.jpg" alt="Description of Image" class="card-image">
                <h3>BSIT</h3>
                <p>Celebrate academic excellence with the Bachelor of Science in Information Technology program at Cebu Technological University – Consolacion Campus. Whether you're gearing up for the future or proudly donning your graduation attire, this program paves the way for success in the dynamic IT field.</p>
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