<?php
$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$faculty = $_POST['faculty_name'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subject = $_POST['subject'];
$student_roll = $_POST['roll'];
$classes_attended = (int)$_POST['classes_attended'];
$total_classes = (int)$_POST['total_classes'];
$student_name = $_POST['name']; // if name update needed

$sql = "UPDATE attendance
        SET student_name = ?, classes_attended = ?, total_classes = ?
        WHERE student_roll = ? AND faculty = ? AND semester = ? AND section = ? AND subject = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siisssss", $student_name, $classes_attended, $total_classes, $student_roll, $faculty, $semester, $section, $subject);
$stmt->execute();

echo "Attendance record updated!";
$stmt->close();
$conn->close();
?>
