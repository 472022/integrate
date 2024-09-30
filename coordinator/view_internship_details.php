<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if ( !isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'];

// Fetch student details and their internships
$sql_student = "SELECT * FROM students WHERE id = $student_id";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();

$sql_internships = "SELECT * FROM internships WHERE student_id = $student_id";
$result_internships = $conn->query($sql_internships);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Internship Details</title>
</head>
<body>
    <h2>Internship Details for <?php echo htmlspecialchars($student['name']); ?></h2>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Position</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Stipend</th>
            <th>Responsibilities</th>
            <th>Supervisor Name</th>
            <th>Supervisor Contact</th>
        </tr>
        <?php while($row = $result_internships->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
            <td><?php echo htmlspecialchars($row['position']); ?></td>
            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
            <td><?php echo htmlspecialchars($row['stipend']); ?></td>
            <td><?php echo htmlspecialchars($row['responsibilities']); ?></td>
            <td><?php echo htmlspecialchars($row['supervisor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['supervisor_contact']); ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
