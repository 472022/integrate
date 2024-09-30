<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT applications.*, students.name AS student_name, jobs.title AS job_title 
                        FROM applications 
                        JOIN students ON applications.student_id = students.id 
                        JOIN jobs ON applications.job_id = jobs.id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Applications</title>
</head>
<body>
    <h2>Job Applications</h2>
    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Job Title</th>
            <th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['student_name']; ?></td>
            <td><?php echo $row['job_title']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
