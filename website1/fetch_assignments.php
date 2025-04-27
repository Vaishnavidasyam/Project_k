<?php
// fetch_assignments.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod"; // Database name where assignments are stored

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get query parameters
$semester = $_GET['semester'] ?? '';
$class = $_GET['class'] ?? '';
$department = $_GET['department'] ?? '';
$room = $_GET['room'] ?? '';

// SQL query to fetch existing assignments
$sql = "
    SELECT subject_name, faculty_names
    FROM subject_faculty_assignment
    WHERE semester = ? 
    AND class = ? 
    AND department = ?
    AND room = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $semester, $class, $department, $room);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $faculties = explode(',', $row['faculty_names']); // Assuming multiple faculty names are stored as comma-separated string
    $assignments[] = [
        'subject' => $row['subject_name'],
        'faculties' => $faculties
    ];
}

// Return the assignments as JSON
echo json_encode($assignments);

$stmt->close();
$conn->close();
?>
