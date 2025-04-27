<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "hod"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get query parameters
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$class = isset($_GET['class']) ? $_GET['class'] : '';
$room = isset($_GET['room']) ? $_GET['room'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';

// Prepare SQL query to fetch assignments
$query = "SELECT subject, faculty FROM subfac WHERE semester = ? AND class = ? AND room = ? AND department = ?";

// Prepare statement
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $semester, $class, $room, $department);

// Execute statement
$stmt->execute();

// Bind result
$stmt->bind_result($subject, $faculty);

// Fetch results and store in an array
$assignments = [];
while ($stmt->fetch()) {
    $assignments[] = [
        'subject' => $subject,
        'faculty' => $faculty
    ];
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return assignments as JSON
header('Content-Type: application/json');
echo json_encode($assignments);
?>
