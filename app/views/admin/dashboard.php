<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/safenets/public/css/admindashboard_styles.css" />

    <script>
        // Function to handle displaying marks/grades fields based on selected exam
        function displayMarksFields() {
            const exam = document.getElementById('exam').value;
            const marksContainer = document.getElementById('marksContainer');
            marksContainer.innerHTML = ''; // Clear previous inputs

            // Display relevant input fields based on selected exam
            if (exam === '1') { // Grade 5 Scholarship
                marksContainer.innerHTML = `
                    <label for="marks">Marks:</label>
                    <input type="number" id="marks" name="marks" min="0" max="100">
                `;
            } else if (exam === '2') { // G.C.E (O/L)
                marksContainer.innerHTML = `
                    <label for="science_grade">Science Grade:</label>
                    <input type="text" id="science_grade" name="science_grade">
                    <label for="math_grade">Math Grade:</label>
                    <input type="text" id="math_grade" name="math_grade">
                    <label for="sinhala_grade">Sinhala Grade:</label>
                    <input type="text" id="sinhala_grade" name="sinhala_grade">
                    <label for="english_grade">English Grade:</label>
                    <input type="text" id="english_grade" name="english_grade">
                    <label for="history_grade">History Grade:</label>
                    <input type="text" id="history_grade" name="history_grade">
                `;
            } else if (exam === '3') { // G.C.E (A/L)
                marksContainer.innerHTML = `
                    <label for="biology_grade">Biology Grade:</label>
                    <input type="text" id="biology_grade" name="biology_grade">
                    <label for="chemistry_grade">Chemistry Grade:</label>
                    <input type="text" id="chemistry_grade" name="chemistry_grade">
                    <label for="physics_grade">Physics Grade:</label>
                    <input type="text" id="physics_grade" name="physics_grade">
                `;
            }
        }

        // Function to handle form submission via AJAX
        function submitForm(event) {
            event.preventDefault(); // Prevent default form submission

            const exam = document.getElementById('exam').value;
            const indexNumber = document.getElementById('indexNumber').value;

            // Validate input
            if (!exam || !indexNumber) {
                alert('Please select an exam and enter an index number.');
                return;
            }

            // Validate index number format based on the selected exam
            if (exam === '1' && indexNumber[0] !== '2') {
                alert('For Grade 5 Scholarship, the index number must start with "2".');
                return;
            } else if (exam === '2' && indexNumber[0] !== '8') {
                alert('For G.C.E (O/L), the index number must start with "8".');
                return;
            } else if (exam === '3' && indexNumber[0] !== '5') {
                alert('For G.C.E (A/L), the index number must start with "5".');
                return;
            }

            // Make an AJAX call to fetch student and exam data
            fetch('/safenets/public/admin/login/dashboard', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    exam: exam,
                    indexNumber: indexNumber
                })
            })
            .then(response => response.text())  // Get raw response as text
            .then(text => {
                console.log('Raw response:', text);  // Log raw response for debugging
                try {
                    const data = JSON.parse(text);  // Try parsing as JSON
                    console.log('Parsed JSON:', data);
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Populate student details
                    document.getElementById('studentName').textContent = data.student.full_name || 'N/A';
                    document.getElementById('studentEmail').textContent = data.student.email || 'N/A';

                    // Display marks/grades based on the exam type
                    displayMarksFields();

                    if (exam === '1' && document.getElementById('marks')) {
                        document.getElementById('marks').value = data.result.marks || '';
                    } else if (exam === '2') {
                        document.getElementById('science_grade').value = data.result.science_grade || 'N/A';
                        document.getElementById('math_grade').value = data.result.math_grade || 'N/A';
                        document.getElementById('sinhala_grade').value = data.result.sinhala_grade || 'N/A';
                        document.getElementById('english_grade').value = data.result.english_grade || 'N/A';
                        document.getElementById('history_grade').value = data.result.history_grade || 'N/A';
                    } else if (exam === '3') {
                        document.getElementById('biology_grade').value = data.result.biology_grade || 'N/A';
                        document.getElementById('chemistry_grade').value = data.result.chemistry_grade || 'N/A';
                        document.getElementById('physics_grade').value = data.result.physics_grade || 'N/A';
                    }
                } catch (error) {
                    console.error('Failed to parse JSON:', error);  // Log the parsing error
                    alert('Failed to process the response. Please check the server.');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);  // Log fetch errors
                alert('An error occurred: ' + error.message);
            });
        }

        // Attach the event listener on page load
        window.onload = function() {
            document.getElementById('examForm').addEventListener('submit', submitForm);
            document.getElementById('exam').addEventListener('change', displayMarksFields);
            document.getElementById('updateResultsButton').addEventListener('click', updateResults);
        };

        // Function to handle results update via AJAX
function updateResults() {
    console.log("updateResults function invoked");
    const indexNumber = document.getElementById('indexNumber').value;
    const exam = document.getElementById('exam').value;
    let resultsData = {};

    // Gather input values based on the selected exam
    if (exam === '1') { // Grade 5 Scholarship
        resultsData = {
            marks: document.getElementById('marks').value,
        };
    } else if (exam === '2') { // G.C.E (O/L)
        resultsData = {
            science_grade: document.getElementById('science_grade').value,
            math_grade: document.getElementById('math_grade').value,
            sinhala_grade: document.getElementById('sinhala_grade').value,
            english_grade: document.getElementById('english_grade').value,
            history_grade: document.getElementById('history_grade').value,
        };
    } else if (exam === '3') { // G.C.E (A/L)
        resultsData = {
            biology_grade: document.getElementById('biology_grade').value,
            chemistry_grade: document.getElementById('chemistry_grade').value,
            physics_grade: document.getElementById('physics_grade').value,
        };
    }

    // Make an AJAX call to update exam results
    fetch('/safenets/public/admin/login/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            indexNumber: indexNumber,
            exam: exam,
            results: resultsData
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error); // Handle error in response
        } else {
            alert('Results updated successfully!'); // Success message
        }
    })
    .catch(error => {
        console.error('Fetch error:', error); // Log fetch errors
        alert('An error occurred: ' + error.message);
    });
}

// Attach the event listener to the button


    </script>
</head>
<body>
    <div class="header">
        <img src="/safenets/public/images/logo.png" alt="Logo" class="logo">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="container">
        <div class="form">
            <form id="examForm" method="POST">
                <div>
                    <label for="exam">Select Exam:</label>
                    <select id="exam" name="exam" required>
                        <option value="" disabled selected>Select an exam</option>
                        <option value="1">Grade 5 Scholarship Examination</option>
                        <option value="2">G.C.E (O/L) Examination</option>
                        <option value="3">G.C.E (A/L) Examination</option>
                    </select>
                </div>

                <div>
                    <label for="indexNumber">Index Number:</label>
                    <input type="text" id="indexNumber" name="indexNumber" required maxlength="8">
                </div>

                <div>
                    <input type="submit" value="Select" class="btn">
                </div>
            </form>

            <div class="student-details">
                <h2>Student Details</h2>
                <p>Full Name: <span id="studentName"></span></p>
                <p>Email: <span id="studentEmail"></span></p>
            </div>

            <div id="marksContainer" class="marks-input">
                <!-- Dynamic marks input fields will be inserted here -->
            </div>

            <div class="action-buttons">
                <input type="button" id="updateResultsButton" value="Update Results" class="btn btn-update" />
            </div>
        </div>

        <div class="right-sidebar">
            <h2>Made with 💙 By</h2>
            <div id="indexNumbersContainer">
                <p>- Mufli Mohideen -</p>
            </div>
            <div class="slogans" style="margin-top: 15px; padding: 10px; color: #0056b3">
                <p style="margin: 0; font-size: 1em; font-weight:800; font-style: italic;">"Choose SafeNets, Where Doenets Wish They Could Be!"</p>
            </div>
                <div class="rules" style="margin-top: 20px; padding: 10px; font-size: 0.9em; color: #333; text-align: justify;">
            <h3 style="font-size: 1.1em; color: #0056b3; text-align: center;">Important Rules:</h3>
            <ul style="list-style-type: disc; padding-left: 20px;">
                <li>Ensure all student information is accurate and up-to-date.</li>
                <li>Respect student privacy and confidentiality at all times.</li>
                <li>Follow proper procedures for data entry and updates.</li>
                <li>Report any discrepancies in exam results immediately.</li>
                <li>Adhere to deadlines for result submissions and updates.</li>
            </ul>
    </div>
        </div>

    </div>
</body>
</html>
