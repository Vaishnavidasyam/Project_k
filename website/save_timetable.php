<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the raw input data
$data = file_get_contents("php://input");
$decodedData = json_decode($data, true);

// Debugging: Check if data is received correctly
if (!$decodedData || !isset($decodedData["timetable"]) || empty($decodedData["timetable"])) {
    echo "No valid timetable received.";
    exit;
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO tt (day, time, subject, faculty) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    echo "SQL error: " . $conn->error;
    exit;
}

// Insert each row
foreach ($decodedData["timetable"] as $entry) {
    $stmt->bind_param("ssss", $entry["day"], $entry["time"], $entry["subject"], $entry["faculty"]);
    if (!$stmt->execute()) {
        echo "Error inserting data: " . $stmt->error;
        exit;
    }
}

// Close connections
$stmt->close();
$conn->close();

echo "Timetable saved successfully!";
?>
