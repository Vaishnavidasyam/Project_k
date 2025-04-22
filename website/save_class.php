<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$department = $_POST['department'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$day = $_POST['day'];
$time = $_POST['time'];
$subject = $_POST['subject'];
$faculty = $_POST['faculty'];
$room = $_POST['room'];

// Check if slot is occupied for department + semester + section
$checkSlot = $conn->prepare("SELECT * FROM class WHERE department = ? AND semester = ? AND section = ? AND day = ? AND time = ?");
$checkSlot->bind_param("sssss", $department, $semester, $section, $day, $time);
$checkSlot->execute();
if ($checkSlot->get_result()->num_rows > 0) {
    echo "exists";
    exit;
}

// Check if faculty is already assigned to another subject in this department-semester-section
$checkFacultySubject = $conn->prepare("SELECT * FROM class WHERE department = ? AND semester = ? AND section = ? AND faculty = ? AND subject != ?");
$checkFacultySubject->bind_param("sssss", $department, $semester, $section, $faculty, $subject);
$checkFacultySubject->execute();
if ($checkFacultySubject->get_result()->num_rows > 0) {
    echo "faculty_exists";
    exit;
}

// Check faculty conflict across all classes
$checkFacultyConflict = $conn->prepare("SELECT * FROM class WHERE day = ? AND time = ? AND faculty = ?");
$checkFacultyConflict->bind_param("sss", $day, $time, $faculty);
$checkFacultyConflict->execute();
if ($checkFacultyConflict->get_result()->num_rows > 0) {
    echo "faculty_conflict";
    exit;
}

// Check room conflict
$checkRoom = $conn->prepare("SELECT * FROM class WHERE day = ? AND time = ? AND room = ?");
$checkRoom->bind_param("sss", $day, $time, $room);
$checkRoom->execute();
if ($checkRoom->get_result()->num_rows > 0) {
    echo "room_conflict";
    exit;
}

// Insert class
$insert = $conn->prepare("INSERT INTO class (department, semester, section, day, time, subject, faculty, room) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("ssssssss", $department, $semester, $section, $day, $time, $subject, $faculty, $room);
if ($insert->execute()) {
    echo "success";
} else {
    echo "error";
}
$conn->close();
?>
