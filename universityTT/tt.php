<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "university_tt");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$semester = $_POST['semester'];
$section = $_POST['section'];

// Insert data into database
$sql = "INSERT INTO timetable_selection (semester, section) VALUES ('$semester', '$section')";
if ($conn->query($sql) === TRUE) {
    // Redirect with semester and section as query params
    header("Location: hodCT1.html?semester=" . urlencode($semester) . "&section=" . urlencode($section));
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
