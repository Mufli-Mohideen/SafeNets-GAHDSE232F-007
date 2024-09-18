<?php if (isset($_SESSION['message'])): ?>
    <script>
        alert("<?php echo $_SESSION['message']; ?>");
    </script>
    <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Department of Examinations - Admin Login</title>
    <link rel="stylesheet" href="/safenets/public/css/signup_styles.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<div class="wrapper">
    <img src="/safenets/public/images/logo.png" alt="Logo" style="display: block; margin: 0 auto; width: 150px; height: auto; margin-bottom: 5px;" />
    <div class="title">
        Admin Login Form
    </div>
    <div class="form">
        <form id="adminLoginForm" action="/safenets/public/admin/login" method="POST" onsubmit="return validateAdminLoginForm()">
            <!-- Username Input -->
            <div class="inputfield">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="input" required />
            </div>

            <!-- Password Input -->
            <div class="inputfield">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="input" required />
            </div>

            <!-- reCAPTCHA -->
            <div class="inputfield" style="display: flex; justify-content: center;">
                <div class="g-recaptcha" data-sitekey="6LdXF0YqAAAAAIdaG3FeBNV8sm1bZmfnzFfCuFye"></div>
            </div>

            <!-- Login Button -->
            <div class="inputfield">
                <input type="submit" value="Login" class="btn">
            </div>
        </form>
    </div>
</div>

<script src="/safenets/public/js/login_script.js"></script>

</body>
</html>
