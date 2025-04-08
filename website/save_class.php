<?php
$conn = new mysqli("localhost", "root", "", "hod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$day = $_POST['day'];
$time = $_POST['time'];
$subject = $_POST['subject'];
$faculty = $_POST['faculty'];
$room = $_POST['room'];
$semester = $_POST['semester'];
$section = $_POST['section'];

$stmt = $conn->prepare("INSERT INTO class (day, time, subject, faculty, room, semester, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $day, $time, $subject, $faculty, $room, $semester, $section);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
