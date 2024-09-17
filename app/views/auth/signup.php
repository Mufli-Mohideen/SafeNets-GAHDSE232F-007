<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Department of Examinations - Student Signup</title>
    <link rel="stylesheet" href="/safenets/public/css/signup_styles.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>

<div class="wrapper">
  <img src="/safenets/public/images/logo.png" alt="Logo" style="display: block; margin: 0 auto; width: 150px; height: auto; margin-bottom: 5px;" />
  <div class="title">
  <div id="message" class="alert" style="display: none;"></div>
      Registration Form
  </div>
  <div class="form">
    <form id="studentSignupForm" action="/safenets/public/student/signup" method="POST" onsubmit="return validateForm()">
      <div class="inputfield">
        <label>Select Exam:</label>
        <div class="custom_select">
          <select id="exam" name="exam" onchange="updateIdLabel()" required>
            <option value="" disabled selected>Select an exam</option>
            <option value="1">Grade 5 Scholarship Examination</option>
            <option value="2">G.C.E (O/L) Examination</option>
            <option value="3">G.C.E (A/L) Examination</option>
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
        <input type="email" name="email" id="email" class="input" required />
      </div>

      <div class="inputfield">
        <div class="g-recaptcha" data-sitekey="6LdXF0YqAAAAAIdaG3FeBNV8sm1bZmfnzFfCuFye"></div>
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
    
    if (examSelect.value === '1') {
      idLabel.textContent = 'Postal ID:';
    } else {
      idLabel.textContent = 'NIC:';
    }
  }

  function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailPattern.test(email);
  }

  function validateNIC(nic) {
    // Validate 10-digit NIC (9 numbers followed by 'V' or 'v')
    if (nic.length === 10) {
      const nicPattern10 = /^[0-9]{9}[vV]$/;
      return nicPattern10.test(nic);
    }
    // Validate 12-digit NIC (all numbers)
    if (nic.length === 12) {
      const nicPattern12 = /^[0-9]{12}$/;
      return nicPattern12.test(nic);
    }
    return false;
  }

  function validatePostalID(postalId) {
    // Postal ID validation logic if needed (for now, we just ensure it's filled in)
    return postalId.trim().length > 0;
  }

  function validateForm() {
    const email = document.getElementById('email').value;
    const id = document.getElementById('id').value;
    const exam = document.getElementById('exam').value;

    // Validate email
    if (!validateEmail(email)) {
      alert("Please enter a valid email address.");
      return false;
    }

    // Validate NIC or Postal ID based on exam selection
    if (exam === '1') {
      // Postal ID validation for Grade 5 Scholarship Examination
      if (!validatePostalID(id)) {
        alert("Please enter a valid Postal ID.");
        return false;
      }
    } else {
      // NIC validation for other exams
      if (!validateNIC(id)) {
        alert("Please enter a valid NIC number.");
        return false;
      }
    }

    // If all validations pass
    return true;
  }

  // JavaScript to convert Full Name to uppercase
document.getElementById('studentSignupForm').addEventListener('input', function(event) {
    if (event.target.name === 'fullName') {
        event.target.value = event.target.value.toUpperCase();
    }
});

// Function to display alert messages based on URL parameter
function showAlert() {
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');

        if (message) {
            alert(message); // Show the alert box with the message
        }
    }

    // Call the function to display alert if there is a message
    showAlert();
</script>

</body>
</html>
