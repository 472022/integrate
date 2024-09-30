<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Retrieve student username from session
$student_username = $_SESSION['student'];

// Get the student's branch from the students table
$sql_student = "SELECT branch FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_branch = $student['branch'];

// Retrieve job listings based on student's branch
$sql_jobs = "SELECT * FROM jobs WHERE domain='$student_branch'";
$result_jobs = $conn->query($sql_jobs);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Jobs</title>
</head>
<body>
    <h2>Job Listings</h2>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Description</th>
            <th>Skills</th>
            <th>Domain</th>
            <th>Position</th>
            <th>Experience</th>
            <th>Salary</th>
            <th>Openings</th>
            <th>Eligibility</th>
            <th>Shift</th>
            <th>Schedule</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result_jobs->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['skills']; ?></td>
            <td><?php echo $row['domain']; ?></td>
            <td><?php echo $row['position']; ?></td>
            <td><?php echo $row['experience']; ?></td>
            <td><?php echo $row['salary']; ?></td>
            <td><?php echo $row['openings']; ?></td>
            <td><?php echo $row['eligibility']; ?></td>
            <td><?php echo $row['shift']; ?></td>
            <td><?php echo $row['schedule']; ?></td>
            <td><a href="apply_job.php?id=<?php echo $row['id']; ?>">Apply</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
