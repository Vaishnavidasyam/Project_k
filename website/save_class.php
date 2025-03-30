<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hod"; // Ensure this is your correct database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$subject = $_POST['subject'];
$staff = $_POST['staff'];
$day = $_POST['day'];
$time = $_POST['time'];

// Insert into 'class' table
$sql = "INSERT INTO class (day, time, subject, faculty) VALUES ('$day', '$time', '$subject', '$staff')";

if ($conn->query($sql) === TRUE) {
    echo "Class details saved successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
