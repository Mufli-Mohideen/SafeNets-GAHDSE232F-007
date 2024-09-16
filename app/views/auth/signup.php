<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Deparment of Examinations - Student Signup</title>
    <link rel="stylesheet" href="../../../public/css/signup_styles.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>

<div class="wrapper">
<img src="../../../public/images/logo.png" alt="Logo" style="display: block; margin: 0 auto; width: 150px; height: auto; margin-bottom: 5px;" />
    <div class="title">
        Registration Form
    </div>
    <div class="form">
        <form id="studentSignupForm" action="process_signup.php" method="POST"> <!-- Adjust the action as needed -->
            <div class="inputfield">
                <label>Select Exam:</label>
                <div class="custom_select">
                    <select id="exam" name="exam" onchange="updateIdLabel()" required>
                        <option value="" disabled selected>Select an exam</option>
                        <option value="al">G.C.E (A/L) Examination</option>
                        <option value="ol">G.C.E (O/L) Examination</option>
                        <option value="grade5">Grade 5 Scholarship Examination</option>
                    </select>
                </div>
            </div>

            <div class="inputfield">
                <label for="id" id="idLabel">NIC:</label>
                <input type="text" id="id" name="id" class="input" required />
            </div>

            <div class="inputfield">
                <label>Full Name:</label>
                <input type="text" name="fullName" class="input" required />
            </div>

            <div class="inputfield">
                <label>Email Address:</label>
                <input type="email" name="email" class="input" required />
            </div>
            <div class="inputfield">
                 <div class="g-recaptcha" data-sitekey="6LdbA0YqAAAAAF1pWZZDOXSzccwKDcC31qK-7jdl"></div>
            </div>

            <div class="inputfield">
                <input type="submit" value="Register" class="btn">
            </div>
        </form>
    </div>
</div>

<script>
    function updateIdLabel() {
        const examSelect = document.getElementById('exam');
        const idLabel = document.getElementById('idLabel');

        if (examSelect.value === 'grade5') {
            idLabel.textContent = 'Postal ID:';
        } else {
            idLabel.textContent = 'NIC:';
        }
    }
</script>

</body>
</html>