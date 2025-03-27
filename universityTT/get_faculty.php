<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_signdb";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch faculty names
$sql = "SELECT name FROM faculty";
$result = $conn->query($sql);

$faculty = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculty[] = $row['name'];
    }
}

echo json_encode($faculty);
$conn->close();
?>