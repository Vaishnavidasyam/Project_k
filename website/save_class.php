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

// Check if a class is already scheduled at this day and time for the selected semester & section
$checkSlot = $conn->prepare("SELECT * FROM class WHERE semester = ? AND section = ? AND day = ? AND time = ?");
$checkSlot->bind_param("ssss", $semester, $section, $day, $time);
$checkSlot->execute();
$resultSlot = $checkSlot->get_result();

if ($resultSlot->num_rows > 0) {
    echo "exists"; // Slot is already occupied
    exit;
}

// Check if the faculty is already assigned a different subject for this semester and section
$checkFaculty = $conn->prepare("SELECT * FROM class WHERE semester = ? AND section = ? AND faculty = ? AND subject != ?");
$checkFaculty->bind_param("ssss", $semester, $section, $faculty, $subject);
$checkFaculty->execute();
$resultFaculty = $checkFaculty->get_result();

if ($resultFaculty->num_rows > 0) {
    echo "faculty_exists"; // Faculty is already assigned another subject
    exit;
}

// Proceed to insert the class
$insert = $conn->prepare("INSERT INTO class (semester, section, day, time, subject, faculty, room) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("sssssss", $semester, $section, $day, $time, $subject, $faculty, $room);

if ($insert->execute()) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
?>
