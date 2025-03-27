<?php
$servername = "localhost";
$username = "root"; // Adjust if needed
$password = ""; // Adjust if needed
$database = "university_TT";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all timetable data
$sql = "SELECT day, time, subject FROM hodtt";
$result = $conn->query($sql);

$timetable = [];

while ($row = $result->fetch_assoc()) {
    $day = strtoupper($row['day']);
    $time = $row['time'];

    $timetable[$day][$time] = $row['subject'];
}

$conn->close();

echo json_encode($timetable);
?>
