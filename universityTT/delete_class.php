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

// Get class ID and delete
$id = $_GET['id'];
$sql = "DELETE FROM timetable WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Class deleted successfully!";
} else {
    echo "Error deleting class: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
