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

// Read the POST data
$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $assignment) {
    $subject = $assignment['subject'];
    $faculty = $assignment['faculty'];
    
    // Insert into the database (ensure no duplicate faculty assignments)
    $stmt = $conn->prepare("INSERT INTO subfac (subject, faculty, semester, class, department, room) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $subject, $faculty, $semester, $class, $department, $room);
    
    if (!$stmt->execute()) {
        echo "Error saving assignment: " . $stmt->error;
        exit();
    }
}

echo "Assignments saved successfully!";
$stmt->close();
$conn->close();
?>
