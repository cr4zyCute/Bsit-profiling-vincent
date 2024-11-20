<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="./images/bsitlogo.png">
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
 <header>
    
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="images/logo.jpg" alt="logo">
                <h2>CodingNepal</h2>
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="#">Home</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
            <button class="login-btn">LOG IN</button>
        </nav>
    </header>
    
    <section id="home" > 
        <h1>BSIT</h1>
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
    <section>
       <div class="form signup_form">
          <form action="#">
            <h2>Signup</h2>
            <div class="input_box">
              <input type="email" placeholder="Enter your email" required />
              <i class="uil uil-envelope-alt email"></i>
            </div>
            <div class="input_box">
              <input type="password" placeholder="Create password" required />
              <i class="uil uil-lock password"></i>
              <i class="uil uil-eye-slash pw_hide"></i>
            </div>
            <div class="input_box">
              <input type="password" placeholder="Confirm password" required />
              <i class="uil uil-lock password"></i>
              <i class="uil uil-eye-slash pw_hide"></i>
            </div>
            <button class="button">Signup Now</button>
            <div class="login_signup">Already have an account? <a href="#" id="login">Login</a></div>
          </form>
        </div>
      </div>
    </section>

    <footer>
        <p>&copy; 2024 BSIT. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </div>
    </footer>

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