<?php
// Database connection parameters
$servername = "localhost "; // e.g., "localhost"
$username = "root";
$password = "";
$database = "faculty";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form data
$name = $conn->real_escape_string(trim($_POST['name']));
$roll = $conn->real_escape_string(trim($_POST['roll']));
$semester = $conn->real_escape_string(trim($_POST['semester']));
$section = $conn->real_escape_string(trim($_POST['section']));
$subject = $conn->real_escape_string(trim($_POST['subject']));

// Validate inputs
if (empty($name) || empty($roll) || empty($semester) || empty($section) || empty($subject)) {
    die("All fields are required.");
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO attendance (name, roll_number, semester, section, subject) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $roll, $semester, $section, $subject);

// Execute the statement
if ($stmt->execute()) {
    echo "New student added successfully";
    // Redirect to staff3.html or another page
    header("Location: staff3.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
