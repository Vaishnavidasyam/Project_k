<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod"; // or your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT subject FROM subfac";
$result = $conn->query($sql);

$subjects = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject'];
  }
}

$conn->close();
echo json_encode($subjects);
?>
