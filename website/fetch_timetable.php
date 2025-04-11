<?php
header('Content-Type: application/json');

if (!isset($_GET['semester']) || !isset($_GET['section'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$semester = $_GET['semester'];
$section = $_GET['section'];

$conn = new mysqli("localhost", "root", "", "hod"); 
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT day, time, subject, faculty FROM tt WHERE semester=? AND section=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $semester, $section);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Query execution failed']);
    exit;
}

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
