<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_tt";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get day and time from GET request
$day = $_GET['day'];
$time = $_GET['time'];

$sql = "SELECT * FROM timetable WHERE day = ? AND time = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $day, $time);
$stmt->execute();
$result = $stmt->get_result();

// Check if any existing record matches
if ($result->num_rows > 0) {
    echo json_encode(["exists" => true]);
} else {
    echo json_encode(["exists" => false]);
}

$stmt->close();
$conn->close();
?>
