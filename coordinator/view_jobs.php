<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM jobs");
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
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
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
        </tr>
        <?php } ?>
    </table>
</body>
</html>
