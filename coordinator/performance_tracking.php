<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['coordinator'])) {
    header("Location: login.php");
    exit();
}

// Step 1: Insert records into performance_tracking if they don't exist
$insert_sql = "INSERT INTO performance_tracking (application_id, aptitude, technical_interview, offer_letter, placed, rejected)
               SELECT a.id, 'pending', 'pending', 'pending', 'pending', 'no'
               FROM applications a
               LEFT JOIN performance_tracking pt ON a.id = pt.application_id
               WHERE a.status = 'accepted' AND pt.application_id IS NULL";

if (!$conn->query($insert_sql)) {
    die("Error inserting data: " . $conn->error);
}

// Step 2: Query to get performance tracking data along with student and job details
$sql = "SELECT 
            pt.application_id, 
            s.name AS student_name, 
            s.email AS student_email, 
            s.phone AS student_phone, 
            s.branch AS student_branch, 
            j.title AS job_title, 
            j.description AS job_description, 
            j.domain AS job_domain, 
            pt.aptitude, 
            pt.technical_interview, 
            pt.offer_letter, 
            pt.placed, 
            pt.rejected
        FROM performance_tracking pt 
        JOIN applications a ON pt.application_id = a.id 
        JOIN students s ON a.student_id = s.id 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.status = 'accepted'";

$result = $conn->query($sql);

// Check if query execution was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Tracking</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
           
        }
        .progress-container {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            margin-bottom: 50px;
        }
        .milestone {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 18%;
            z-index: 2;
        }
        .milestone-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
        }
        .milestone.active .milestone-icon {
            background-color: #4CAF50;
            color: white;
            box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.2);
        }
        .milestone.rejected .milestone-icon {
            background-color: #ff0000;
            color: white;
            box-shadow: 0 0 0 5px rgba(255, 0, 0, 0.2);
        }
        .milestone-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .milestone-desc {
            font-size: 14px;
            color: #666;
            max-width: 120px;
        }
        .progress-line {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            height: 4px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .progress-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <h2>Performance Tracking</h2>
        <table border="1" style="width: 100%; margin-bottom: 20px;">
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Branch</th>
                <th>Job Title</th>
                <th>Job Description</th>
                <th>Job Domain</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>
            <?php
            // Check if there are any records to display
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Determine progress fill percentage based on the completion stages
                    $totalStages = 5;
                    $completedStages = 0;
                    if ($row['aptitude'] == 'completed') $completedStages++;
                    if ($row['technical_interview'] == 'completed') $completedStages++;
                    if ($row['offer_letter'] == 'completed') $completedStages++;
                    if ($row['placed'] == 'completed') $completedStages++;
                    if ($row['rejected'] == 'yes') $completedStages = 0; // Reset if rejected
                    $progressPercentage = ($completedStages / $totalStages) * 100;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_branch']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_domain']); ?></td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-line">
                                    <div class="progress-line-fill" style="width: <?php echo $progressPercentage; ?>%;"></div>
                                </div>

                                <div class="milestone <?php echo $row['aptitude'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                    <div class="milestone-icon">1</div>
                                    <div class="milestone-title">Aptitude</div>
                                    <div class="milestone-desc">Aptitude Test</div>
                                </div>

                                <div class="milestone <?php echo $row['technical_interview'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                    <div class="milestone-icon">2</div>
                                    <div class="milestone-title">Technical Interview</div>
                                    <div class="milestone-desc">Technical Interview</div>
                                </div>

                                <div class="milestone <?php echo $row['offer_letter'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                    <div class="milestone-icon">3</div>
                                    <div class="milestone-title">Offer Letter</div>
                                    <div class="milestone-desc">Offer Letter Sent</div>
                                </div>

                                <div class="milestone <?php echo $row['placed'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                    <div class="milestone-icon">4</div>
                                    <div class="milestone-title">Placed</div>
                                    <div class="milestone-desc">Placed in Company</div>
                                </div>

                                <div class="milestone <?php echo $row['rejected'] == 'yes' ? 'rejected' : ''; ?>">
                                    <div class="milestone-icon">5</div>
                                    <div class="milestone-title">Rejected</div>
                                    <div class="milestone-desc">Rejected from Process</div>
                                </div>
                            </div>
                        </td>
                        <td><a href="edit_performance.php?id=<?php echo $row['application_id']; ?>">Edit</a></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>No records found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
