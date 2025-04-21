<?php
$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$faculty = $_POST['faculty'];
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
?> fetch_attendance,php: <?php
$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$faculty = $_GET['faculty_name'];
$semester = $_GET['semester'];
$section = $_GET['section'];
$subject = $_GET['subject'];
$sql = "SELECT student_roll, student_name, classes_attended, total_classes,
               ROUND((classes_attended / total_classes) * 100, 2) AS percentage
        FROM attendance
        WHERE faculty = ? AND semester = ? AND section = ? AND subject = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $faculty, $semester, $section, $subject);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
$stmt->close();
$conn->close();
?> 