<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faculty";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect data from POST
$roll = $_POST['roll'];
$semester = $_POST['semester'];
$faculty = $_POST['faculty'];
$subject = $_POST['subject']; // optional if you want to be even more precise

// Prepare the SQL query with multiple conditions
$sql = "DELETE FROM students WHERE roll = ? AND semester = ? AND faculty = ? AND subject = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $roll, $semester, $faculty, $subject);

if ($stmt->execute()) {
  echo "Student deleted successfully!";
} else {
  echo "Error deleting student: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
