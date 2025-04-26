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

// Check if the combination already exists
$query = "SELECT * FROM subjects WHERE subject_name = ? AND semester = ? AND class = ? AND department = ? AND room = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $subject_name, $semester, $class, $department, $room);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "This subject is already assigned to this combination of semester, class, room, and department."]);
    $stmt->close();
    $conn->close();
    exit();
}

// Prepare and bind the insert query
$insert_stmt = $conn->prepare("INSERT INTO subjects (subject_name, semester, class, department, room) VALUES (?, ?, ?, ?, ?)");
$insert_stmt->bind_param("sssss", $subject_name, $semester, $class, $department, $room);

// Execute the query
if ($insert_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Subject saved successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving subject: " . $insert_stmt->error]);
}

// Close the statements and connection
$insert_stmt->close();
$stmt->close();
$conn->close();
?>
