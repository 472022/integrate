<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Get student ID from URL
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
} else {
    header("Location: view_student_history.php");
    exit();
}

// Query to get application details for the student
$sql = "SELECT 
            a.id AS application_id,
            j.title AS job_title,
            j.description AS job_description,
            j.domain AS job_domain,
            a.status AS application_status
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE a.student_id = '$student_id'";

$result = $conn->query($sql);

// Query to get student details
$sql_student = "SELECT name, email, phone, branch FROM students WHERE id = '$student_id'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Application Details for <?php echo htmlspecialchars($student['name']); ?></h2>
    <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($student['phone']); ?></p>
    <p>Branch: <?php echo htmlspecialchars($student['branch']); ?></p>
    
    <h3>Applications</h3>
    <table>
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Job Description</th>
                <th>Job Domain</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_domain']); ?></td>
                        <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='4'>No applications found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="view_student_history.php">Back to History</a>
</body>
</html>
