<?php
header('Content-Type: application/json');

if (!isset($_GET['semester']) || !isset($_GET['section']) || !isset($_GET['department'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$semester = $_GET['semester'];
$section = $_GET['section'];
$department = $_GET['department'];

$conn = new mysqli("localhost", "root", "", "hod");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT day, time, subject, faculty FROM tt WHERE semester=? AND section=? AND department=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $semester, $section, $department);

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
