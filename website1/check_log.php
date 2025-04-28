<?php
$servername = "localhost";
$username = "root"; // your XAMPP username
$password = ""; // your XAMPP password
$dbname = "hod";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get data from URL parameters
$semester = $_GET['semester'];
$class = $_GET['class'];
$room = $_GET['room'];

// Check if the exact combination of semester, class, and room exists in the database
$sql = "SELECT * FROM timetable WHERE semester = ? AND class = ? AND room = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $semester, $class, $room);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If the same combination is found, it's allowed
    echo json_encode(['room_exists' => true, 'semclass_exists' => true]);
} else {
    // Check if the room is already assigned to any other semester/class (not the same combination)
    $sql_check_room = "SELECT * FROM timetable WHERE room = ? AND (semester != ? OR class != ?)";
    $stmt_check_room = $conn->prepare($sql_check_room);
    $stmt_check_room->bind_param("sss", $room, $semester, $class);
    $stmt_check_room->execute();
    $result_check_room = $stmt_check_room->get_result();

    // If the room is occupied by a different semester/class, prevent the booking
    if ($result_check_room->num_rows > 0) {
        echo json_encode(['room_exists' => true, 'semclass_exists' => false]);
    } else {
        // Otherwise, no conflicts
        echo json_encode(['room_exists' => false, 'semclass_exists' => false]);
    }

    $stmt_check_room->close();
}

$stmt->close();
?>
