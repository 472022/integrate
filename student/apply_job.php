<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_username = $_SESSION['student'];
    $sql_student = "SELECT id FROM students WHERE username='$student_username'";
    $result_student = $conn->query($sql_student);
    $student = $result_student->fetch_assoc();
    $student_id = $student['id'];

    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $domain = $_POST['domain'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];

    // Handling file uploads
    $resume = $_FILES['resume']['name'];
    $photo = $_FILES['photo']['name'];
    $target_dir = "../uploads/";

    // Define the full paths for the files
    $target_file_resume = $target_dir . basename($_FILES["resume"]["name"]);
    $target_file_photo = $target_dir . basename($_FILES["photo"]["name"]);

    // Move the uploaded files to the target directory
    move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file_resume);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file_photo);

    // Insert into the database
    $sql = "INSERT INTO applications (student_id, job_id, name, year, branch, domain, address, phone, email, experience, resume, photo) 
            VALUES ('$student_id', '$job_id', '$name', '$year', '$branch', '$domain', '$address', '$phone', '$email', '$experience', '$target_file_resume', '$target_file_photo')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully";
        header("Location: view_applications.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$job_result = $conn->query("SELECT * FROM jobs WHERE id='$job_id'");
$job = $job_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Apply Job</title>
</head>
<body>
    <h2>Apply for Job: <?php echo $job['title']; ?></h2>
    <form method="POST" enctype="multipart/form-data" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" required><br>
        
        <label for="branch">Branch:</label>
        <input type="text" id="branch" name="branch" required><br>
        
        <label for="domain">Domain of Work:</label>
        <input type="text" id="domain" name="domain" required><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        
        <label for="phone">Phone No:</label>
        <input type="text" id="phone" name="phone" required><br>
        
        <label for="email">Email (Personal):</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="experience">Short Description about Experience:</label>
        <textarea id="experience" name="experience" required></textarea><br>
        
        <label for="resume">Upload Resume (PDF):</label>
        <input type="file" id="resume" name="resume" accept="application/pdf" required><br>

        <label for="photo">Upload Photo (JPEG, PNG):</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required><br>
        
        <input type="submit" value="Apply">
    </form>
</body>
</html>
