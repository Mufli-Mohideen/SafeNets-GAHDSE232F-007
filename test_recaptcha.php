<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Test</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<h2>Test reCAPTCHA</h2>

<form action="test_recaptcha.php" method="POST">
    <div class="g-recaptcha" data-sitekey="6LdXF0YqAAAAAIdaG3FeBNV8sm1bZmfnzFfCuFye"></div>
    <br>
    <input type="submit" value="Verify reCAPTCHA">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6LdXF0YqAAAAAMgQ0m4QBcsPEFO5KxWj52BVYJsd';

    // Verify the reCAPTCHA response
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
    $responseData = json_decode($verifyResponse);

    // Check if the verification was successful
    if ($responseData->success) {
        echo "<p>reCAPTCHA verification successful!</p>";
    } else {
        echo "<p>reCAPTCHA verification failed. Please try again.</p>";
    }
}
?>

</body>
</html>
