<?php
// Check if the student is logged in and exam results are set
if (!isset($_SESSION['student']) || !isset($_SESSION['examResults'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve student and exam results from session
$examResults = $_SESSION['examResults'];
$student = $_SESSION['student'];

// Set exam name and subject list based on the eid
$examName = '';
$results = [];

switch ($student['eid']) {
    case 2: // G.C.E (O/L) Examination
        $examName = 'G.C.E (O/L) Examination';
        $results = [
            'Science' => $examResults['science_grade'],
            'Math' => $examResults['math_grade'],
            'Sinhala' => $examResults['sinhala_grade'],
            'English' => $examResults['english_grade'],
            'History' => $examResults['history_grade']
        ];
        break;
    case 3: // G.C.E (A/L) Examination
        $examName = 'G.C.E (A/L) Examination';
        $results = [
            'Biology' => $examResults['biology_grade'],
            'Physics' => $examResults['physics_grade'],
            'Chemistry' => $examResults['chemistry_grade']
        ];
        break;
    case 1: // Grade 5 Scholarship Examination
        $examName = 'Grade 5 Scholarship Examination';
        $results = [
            'Marks' => $examResults['marks']
        ];
        break;
    default:
        header('Location: login.php'); // Redirect if eid is invalid
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results - Department of Examinations</title>
    <link rel="stylesheet" href="/safenets/public/css/signup_styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .title {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .result-label {
            font-weight: bold;
            margin-right: 10px;
        }
        .logout-button {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
        }
        .logout-button:hover {
            background-color: #c9302c;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .results-table th, .results-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }
        .results-table th {
            background-color: #f2f2f2;
            color: #0056b3;
        }
        .results-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        p {
            text-align: center;
            display: block;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <img src="/safenets/public/images/logo.png" alt="Logo" style="display: block; margin: 0 auto; width: 150px; height: auto; margin-bottom: 15px;" />
    
    <div class="title">Exam Results</div>

    <p>
        <span class="result-label">Name:</span>
        <label><?php echo htmlspecialchars($student['full_name']); ?></label>
    </p>
    
    <p>
        <span class="result-label">Index Number:</span>
        <label><?php echo htmlspecialchars($student['index_number']); ?></label>
    </p>
    
    <p>
        <span class="result-label">Exam:</span>
        <label><?php echo htmlspecialchars($examName); ?></label>
    </p>

    <table class="results-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $subject => $grade): ?>
                <tr>
                    <td><?php echo htmlspecialchars($subject); ?></td>
                    <td><?php echo htmlspecialchars($grade); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="/safenets/public/student/logout" method="POST">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

</body>
</html>
