<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "subjects";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT subject_name FROM subjects";
$result = $conn->query($sql);

$subjects = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
  }
}

echo json_encode($subjects);

$conn->close();
?>
