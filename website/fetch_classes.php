<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all class data
$sql = "SELECT * FROM class";
$result = $conn->query($sql);

$classes = array();
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

echo json_encode($classes);

$conn->close();
?>
