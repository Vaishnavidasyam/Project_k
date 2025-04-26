<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the query parameters
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$class = isset($_GET['class']) ? $_GET['class'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$room = isset($_GET['room']) ? $_GET['room'] : '';

// Fetch assignments from the subfac table
$query = "SELECT subject, faculty FROM subfac WHERE semester = ? AND class = ? AND department = ? AND room = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $semester, $class, $department, $room);

$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode($assignments); // Return as JSON
$stmt->close();
$conn->close();
?>
