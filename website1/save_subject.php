<?php 
// Set content type to JSON
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get POST data
$subject_name = $_POST['subject'] ?? '';
$semester = $_POST['semester'] ?? '';
$class = $_POST['class'] ?? '';
$room = $_POST['room'] ?? '';
$department = $_POST['department'] ?? '';

// Check if any required fields are empty
if (empty($subject_name) || empty($semester) || empty($class) || empty($room) || empty($department)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Prepare and bind the query
$stmt = $conn->prepare("INSERT INTO subjects (subject_name, semester, class, department, room) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $subject_name, $semester, $class, $department, $room);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Subject saved successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving subject: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
