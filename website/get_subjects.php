<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "hod";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$facultyName = $_GET['name'] ?? '';
$subjects = [];

if ($facultyName !== '') {
  $stmt = $conn->prepare("SELECT semester, section, subject FROM tt WHERE faculty = ?");
  $stmt->bind_param("s", $facultyName);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
  }

  $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($subjects);
?>
