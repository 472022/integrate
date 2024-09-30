<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM students WHERE id='$student_id'");
    $student = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prn = $_POST['prn'];
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $division = $_POST['division'];
    $batch = $_POST['batch'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "UPDATE students SET prn='$prn', roll_no='$roll_no', name='$name', year='$year', branch='$branch', division='$division', 
            batch='$batch', address='$address', email='$email', phone='$phone', username='$username', password='$password' 
            WHERE id='$student_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_students.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h2>Edit Student</h2>
    <form method="POST" action="">
        <label for="prn">PRN:</label>
        <input type="text" id="prn" name="prn" value="<?php echo $student['prn']; ?>" required><br>
        
        <label for="roll_no">Roll No:</label>
        <input type="text" id="roll_no" name="roll_no" value="<?php echo $student['roll_no']; ?>" required><br>
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $student['name']; ?>" required><br>
        
        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo $student['year']; ?>" required><br>
        
        <label for="branch">Branch:</label>
        <input type="text" id="branch" name="branch" value="<?php echo $student['branch']; ?>" required><br>
        
        <label for="division">Division:</label>
        <input type="text" id="division" name="division" value="<?php echo $student['division']; ?>" required><br>
        
        <label for="batch">Batch:</label>
        <input type="text" id="batch" name="batch" value="<?php echo $student['batch']; ?>" required><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $student['address']; ?>" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>" required><br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $student['phone']; ?>" required><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $student['username']; ?>" required><br>

        <label for="password">password:</label>
        <input type="text" id="password" name="password" value="<?php echo $student['password']; ?>" required><br>
        
        <input type="submit" value="Update Student">
    </form>
</body>
</html>
