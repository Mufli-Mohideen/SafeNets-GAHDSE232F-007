<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department of examinations</title>
    <link rel="stylesheet" href="css/index_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
        <img src="images/banner.jpg" alt="Banner" class="banner">
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <!-- Left-side links -->
            <li><a href="#">Home</a></li>
            <li><a href="#">Exam Calendar</a></li>
            <li><a href="#">Certificates / Verification Results</a></li>
            <li><a href="#">Contact Us</a></li>

            <!-- Right-side elements -->
            <div class="navbar-right">
                <!-- Search bar -->
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <button type="submit">
                        <i class="fas fa-search"></i> <!-- FontAwesome search icon -->
                    </button>
                </div>

                <!-- Login and Register buttons -->
                <a href="login.php" class="btn">Login</a>
                <a href="signup.php" class="btn btn-register">Register</a>
            </div>
        </ul>
    </nav>

    <div class="slider">
  <img id="img-1" src="images/pic1.jpg" alt="Image 1" />
  <img id="img-2" src="images/pic2.jpg" alt="Image 2" />
  <img id="img-3" src="images/pic3.jpg" alt="Image 3" />
  <img id="img-4" src="images/pic4.jpg" alt="Image 4" />
</div>

<!-- Navigation Dots -->
<div class="navigation-button">
  <span class="dot active" onclick="changeSlide(0)"></span>
  <span class="dot" onclick="changeSlide(1)"></span>
  <span class="dot" onclick="changeSlide(2)"></span>
  <span class="dot" onclick="changeSlide(3)"></span>
</div>

<img src="images/hotline.png" alt="Hotline" class="hotline-img" />

<script src="js/index_script.js"></script>

</body>
</html>
