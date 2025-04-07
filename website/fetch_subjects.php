<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hod";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$semester = $_GET['semester'] ?? '';
$section = $_GET['section'] ?? '';

if ($semester && $section) {
  $stmt = $conn->prepare("SELECT DISTINCT subject_name FROM subjects WHERE semester = ? AND section = ?");
  $stmt->bind_param("ss", $semester, $section);
  $stmt->execute();
  $result = $stmt->get_result();

  $subjects = [];
  while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
  }

  echo json_encode($subjects);
} else {
  echo json_encode([]);
}

$conn->close();
?>
