<?php
$semester = $_GET['semester'];
$section = $_GET['section'];

$conn = new mysqli("localhost", "root", "", "hod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all faculty names from the 'faculty' table in 'signin' database
$allFaculty = [];
$facultyResult = $conn->query("SELECT name FROM signin.faculty");
while ($row = $facultyResult->fetch_assoc()) {
    $allFaculty[] = $row['name'];
}

// Get faculty already assigned in 'class' table for the specific semester AND section
$assignedFaculty = [];
$assignedResult = $conn->query("SELECT faculty FROM class WHERE semester='$semester' AND section='$section'");
while ($row = $assignedResult->fetch_assoc()) {
    $assignedFaculty[] = $row['faculty'];
}

echo json_encode([
    "all" => $allFaculty,
    "assigned" => $assignedFaculty
]);

$conn->close();
?>
