<?php
$connection = new mysqli("localhost", "root", "", "hod");

// Get semester and department from the GET request
$semester = $_GET['semester'];
$department = $_GET['department'];

// Prepare the SQL query to fetch subjects based on both semester and department
$query = $connection->prepare("SELECT DISTINCT subject_name FROM subjects WHERE semester = ? AND department = ?");
$query->bind_param("ss", $semester, $department);
$query->execute();

$result = $query->get_result();
$subjects = [];

while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
}

// Return the result as JSON
echo json_encode($subjects);

// Clean up
$query->close();
$connection->close();
?>
