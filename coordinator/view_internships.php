<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if ( !isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students and count their internships
$sql = "SELECT s.id, s.name, s.email, COUNT(i.id) AS internship_count
        FROM students s
        LEFT JOIN internships i ON s.id = i.student_id
        GROUP BY s.id";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>View Internships</title>
</head>
<body>
    <h2>Student Internships</h2>
    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Email</th>
            <th>Number of Internships</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['internship_count']; ?></td>
            <td><a href="view_internship_details.php?student_id=<?php echo $row['id']; ?>">Details</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
