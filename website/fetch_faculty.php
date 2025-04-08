<?php
$servername = "localhost";
$username = "root"; // Change if you have a different MySQL user
$password = ""; // Change if you have a password set for MySQL
$database = "signin"; // Use the 'signin' database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch faculty names
$sql = "SELECT name FROM faculty";
$result = $conn->query($sql);

$facultyList = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $facultyList[] = $row["name"];
    }
}

// Return JSON response
echo json_encode($facultyList);

$conn->close();
?>
