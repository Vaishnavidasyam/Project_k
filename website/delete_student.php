<?php
$roll = $_POST['roll'];
$semester = $_POST['semester'];
$faculty = $_POST['faculty'];
$subject = $_POST['subject'];

$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete from students table
$stmt1 = $conn->prepare("DELETE FROM students WHERE roll = ? AND semester = ? AND subject = ? AND faculty = ?");
$stmt1->bind_param("ssss", $roll, $semester, $subject, $faculty);
$stmt1->execute();

// Delete from attendance table
$stmt2 = $conn->prepare("DELETE FROM attendance WHERE student_roll = ? AND semester = ? AND subject = ? AND faculty = ?");
$stmt2->bind_param("ssss", $roll, $semester, $subject, $faculty);
$stmt2->execute();

if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
    echo "Deleted successfully.";
} else {
    echo "No matching records found.";
}

$stmt1->close();
$stmt2->close();
$conn->close();
?>
