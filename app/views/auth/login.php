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
    <title>Department of Examinations - Login</title>
    <link rel="stylesheet" href="/safenets/public/css/signup_styles.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- External JS -->
</head>
<body>

<div class="wrapper">
    <img src="/safenets/public/images/logo.png" alt="Logo" style="display: block; margin: 0 auto; width: 150px; height: auto; margin-bottom: 5px;" />
    <div class="title">
        <div id="message" class="alert" style="display: none;"></div>
        Login Form
    </div>
    <div class="form">
        <form id="loginForm" action="/safenets/public/student/login" method="POST" onsubmit="console.log('Form submitted'); return validateLoginForm()">
            <div class="inputfield">
                <label>Select Exam:</label>
                <div class="custom_select">
                    <select id="exam" name="exam" required>
                        <option value="" disabled selected>Select an exam</option>
                        <option value="1">Grade 5 Scholarship Examination</option>
                        <option value="2">G.C.E (O/L) Examination</option>
                        <option value="3">G.C.E (A/L) Examination</option>
                    </select>
                </div>
            </div>

            <!-- Index Number Input -->
            <div class="inputfield">
                <label for="indexNumber">Index Number:</label>
                <input type="text" id="indexNumber" name="indexNumber" class="input" required maxlength="8" />
            </div>

            <div class="inputfield">
                <div class="g-recaptcha" id="recaptcha" data-sitekey="6LdXF0YqAAAAAIdaG3FeBNV8sm1bZmfnzFfCuFye"></div>
            </div>

            <!-- Verification Button -->
            <div class="inputfield">
                <input type="button" value="Send Verification Code" class="btn" id="sendOtpBtn" onclick="sendOTP()">
            </div>

            <!-- OTP Input and Countdown Timer -->
            <div id="otpSection" style="display: none;">
                <div class="inputfield">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" class="input" maxlength="5" required />
                </div>

                <div class="inputfield">
                    <p id="timer" style="display: inline-block; width: 100px; text-align: right; margin-right: 250px;">Time left: 05:00</p>
                    <button type="button" id="resendOtp" class="btn-resend" onclick="resendOTP()" disabled>Resend OTP</button>
                </div>
            </div>

            <!-- Submit Button (Initially Hidden) -->
            <div class="inputfield" style="margin-top: 20px; display: none;" id="verifyLoginBtn">
                <input type="submit" value="Verify & Login" class="btn">
            </div>

        </form>
    </div>
</div>

<script src="/safenets/public/js/login_script.js"></script>


</body>
</html>
