<?php
// Fetch assignments based on query parameters (semester, class, department, room)
$semester = $_GET['semester'];
$class = $_GET['class'];
$department = $_GET['department'];
$room = $_GET['room'];

$conn = new mysqli("localhost", "username", "password", "hod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT subject, faculty FROM subfac WHERE semester = ? AND class = ? AND department = ? AND room = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $semester, $class, $department, $room);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];

while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode($assignments);

$conn->close();
?>
