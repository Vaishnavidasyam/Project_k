<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$semester = $_POST['semester'];
$section = $_POST['section'];
$day = $_POST['day'];
$time = $_POST['time'];
$subject = $_POST['subject'];
$faculty = $_POST['faculty'];
$room = $_POST['room'];

// Check if a class is already scheduled at this day and time for the same semester & section
$checkSlot = $conn->prepare("SELECT * FROM class WHERE semester = ? AND section = ? AND day = ? AND time = ?");
$checkSlot->bind_param("ssss", $semester, $section, $day, $time);
$checkSlot->execute();
$resultSlot = $checkSlot->get_result();

if ($resultSlot->num_rows > 0) {
    echo "exists"; // Slot is already occupied for this class
    exit;
}

// Check if the faculty is already assigned a different subject for this semester and section
$checkFacultySubject = $conn->prepare("SELECT * FROM class WHERE semester = ? AND section = ? AND faculty = ? AND subject != ?");
$checkFacultySubject->bind_param("ssss", $semester, $section, $faculty, $subject);
$checkFacultySubject->execute();
$resultFacultySubject = $checkFacultySubject->get_result();

if ($resultFacultySubject->num_rows > 0) {
    echo "faculty_exists"; // Faculty is assigned to another subject in same semester-section
    exit;
}

// ✅ Check if faculty is already teaching something else at the same day and time (across all semester/section)
$checkFacultyConflict = $conn->prepare("SELECT * FROM class WHERE day = ? AND time = ? AND faculty = ?");
$checkFacultyConflict->bind_param("sss", $day, $time, $faculty);
$checkFacultyConflict->execute();
$resultFacultyConflict = $checkFacultyConflict->get_result();

if ($resultFacultyConflict->num_rows > 0) {
    echo "faculty_conflict"; // Faculty is teaching at this time
    exit;
}

// ✅ Check if room is already occupied at the same day and time (across all semester/section)
$checkRoom = $conn->prepare("SELECT * FROM class WHERE day = ? AND time = ? AND room = ?");
$checkRoom->bind_param("sss", $day, $time, $room);
$checkRoom->execute();
$resultRoom = $checkRoom->get_result();

if ($resultRoom->num_rows > 0) {
    echo "room_conflict"; // Room is already used at this time
    exit;
}

// All checks passed — Insert the class
$insert = $conn->prepare("INSERT INTO class (semester, section, day, time, subject, faculty, room) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("sssssss", $semester, $section, $day, $time, $subject, $faculty, $room);

if ($insert->execute()) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
?>
