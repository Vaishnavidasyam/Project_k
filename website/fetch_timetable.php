<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "hod";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch timetable data including faculty names
$sql = "SELECT day, time, subject, faculty FROM tt";
$result = $conn->query($sql);

$timetable = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = $row;
    }
}

// Return JSON response
echo json_encode($timetable);

$conn->close();
?>
