<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_tt";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch timetable data
$sql = "SELECT id, day, time, subject, faculty FROM timetable";
$result = $conn->query($sql);

$timetable = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = $row;
    }
}

echo json_encode($timetable);
$conn->close();
?>
