<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hod";  // Use your DB name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch subject-faculty mappings
$sql = "SELECT subject_name, faculty_name FROM subject_faculty"; // adjust table name
$result = $conn->query($sql);

$subjects = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
  }
}

echo json_encode($subjects);

$conn->close();
?>
