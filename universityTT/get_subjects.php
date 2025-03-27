<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "subjects");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch subjects
$sql = "SELECT subject_name FROM subjects ORDER BY subject_name ASC";
$result = $conn->query($sql);

$subjects = [];

// Fetch and store subjects in an array
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
}

// Return JSON response
echo json_encode($subjects);

$conn->close();
?>
