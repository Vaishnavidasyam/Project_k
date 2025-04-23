<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$faculty = $_GET['faculty'];

$sql = "SELECT day, time, subject, section, semester, room FROM tt WHERE faculty = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $faculty);
$stmt->execute();
$result = $stmt->get_result();

$timetable = [];

while ($row = $result->fetch_assoc()) {
  $timetable[] = $row;
}

echo json_encode($timetable);

$conn->close();
?>
