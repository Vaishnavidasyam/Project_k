<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faculty";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$roll = $_POST['roll'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subject = $_POST['subject'];
$faculty = $_POST['faculty'];

$sql = "INSERT INTO students (name, roll, semester, section, subject, faculty) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $roll, $semester, $section, $subject, $faculty);

if ($stmt->execute()) {
  echo "<script>alert('Student added successfully!'); window.location.href = 'staff3.html?name=" . urlencode($faculty) . "&semester=" . urlencode($semester) . "&section=" . urlencode($section) . "&subject=" . urlencode($subject) . "';</script>";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
