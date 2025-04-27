<?php
// Database connection
$servername = "localhost";
$username = "root"; // Adjust with your database username
$password = ""; // Adjust with your database password
$dbname = "hod"; // Adjust with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$assignments = json_decode(file_get_contents('php://input'), true);

foreach ($assignments as $assignment) {
    $subject = $assignment['subject'];
    $faculty = $assignment['faculty'];
    $semester = $assignment['semester'];
    $class = $assignment['class'];
    $department = $assignment['department'];
    $room = $assignment['room'];

    // Check if faculty is already assigned to another subject
    if (strpos($faculty, ',') === false) {
        // Single faculty assignment
        $checkQuery = "SELECT * FROM subfac WHERE faculty = ? AND semester = ? AND class = ? AND room = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param('ssss', $faculty, $semester, $class, $room);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Error: Faculty is already assigned
            echo json_encode(['success' => false, 'message' => 'Faculty already assigned to another subject']);
            exit();
        }
    }

    // Save the assignment into the subfac table
    $insertQuery = "INSERT INTO subfac (subject, faculty, semester, class, department, room) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('ssssss', $subject, $faculty, $semester, $class, $department, $room);
    $stmt->execute();
}

// Close the connection
$stmt->close();
$conn->close();

// Success response
echo json_encode(['success' => true]);
?>
