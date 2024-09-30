<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

// Query to get the history of students
$sql = "SELECT 
            s.id AS student_id,
            s.name AS student_name,
            s.email AS student_email,
            s.phone AS student_phone,
            s.branch AS student_branch,
            COUNT(a.id) AS application_count
        FROM students s
        LEFT JOIN applications a ON s.id = a.student_id
        GROUP BY s.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application History</title>
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
        .details-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .details-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Student Application History</h2>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Branch</th>
                <th>Number of Applications</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $student_id = $row['student_id'];
                    $app_count = $row['application_count'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_branch']); ?></td>
                        <td><?php echo htmlspecialchars($app_count); ?></td>
                        <td><a href="student_application_details.php?student_id=<?php echo $student_id; ?>" class="details-btn">View Details</a></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6'>No students found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
