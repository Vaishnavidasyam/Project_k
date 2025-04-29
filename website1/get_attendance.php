<?php
$roll = $_GET['rollnumber'] ?? '';

if (!$roll) {
  echo json_encode([]);
  exit;
}

$servername = "localhost";
$username = "root";  // Change this if needed
$password = "";      // Change this if needed
$dbname = "faculty"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT subject, classes_attended, total_classes, percentage, faculty 
        FROM attendance 
        WHERE roll = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roll);
$stmt->execute();

$result = $stmt->get_result();
$attendanceData = [];

while ($row = $result->fetch_assoc()) {
  $attendanceData[] = $row;
}

echo json_encode($attendanceData);
$conn->close();
?>
