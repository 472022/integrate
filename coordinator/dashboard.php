<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Coordinator Dashboard</title>
</head>
<body>
    <h1>Welcome, Coordinator</h1>
    <nav>
        <ul>
            <li><a href="view_jobs.php">View Jobs</a></li>
            <li><a href="view_applications.php">View Applications</a></li>
            <li><a href="view_students.php">View Students</a></li>
            <li><a href="performance_tracking.php">Preformance track</a></li>
            <li><a href="view_internships.php">View Internship Data</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
