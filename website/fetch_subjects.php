<?php
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password is empty
$database = "hod"; // Use the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects from the `subjects` table
$sql = "SELECT subject_name FROM subjects"; // Adjust column name if needed
$result = $conn->query($sql);

$subjects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row["subject_name"];
    }
}

// Return JSON response
echo json_encode($subjects);

$conn->close();
?>
