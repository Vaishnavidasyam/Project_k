<?php
// Database connection
$servername = "localhost";
$username = "root"; // replace with your MySQL username
$password = ""; // replace with your MySQL password
$dbname = "hod"; // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$subject_name = isset($_POST['subject']) ? $_POST['subject'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : '';
$room = isset($_POST['room']) ? $_POST['room'] : '';  // Get the room data
$department = isset($_POST['department']) ? $_POST['department'] : '';

// Check if any required fields are empty
if (empty($subject_name) || empty($semester) || empty($class) || empty($room) || empty($department)) {
    echo "All fields are required.";
    exit();
}

// Prepare and bind the query
$stmt = $conn->prepare("INSERT INTO subjects (subject_name, semester, class, department, room) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $subject_name, $semester, $class, $department, $room); // Added room as a parameter

// Execute the query
if ($stmt->execute()) {
    echo "Subject saved successfully!";
} else {
    echo "Error saving subject: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
