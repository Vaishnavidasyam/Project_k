<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_tt";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from hodCT2.html
$subject = $_POST['subject'];
$faculty = $_POST['staff'];
$day = $_POST['day'];
$time = $_POST['time'];

// Insert class into timetable table
$sql = "INSERT INTO timetable (subject, faculty, day, time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $subject, $faculty, $day, $time);

if ($stmt->execute()) {
    header("Location: hodCT1.html"); // Redirect after successful save
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
