<?php
$conn = new mysqli("localhost", "root", "", "faculty");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$faculty = $_POST['faculty_name'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subject = $_POST['subject'];
$student_roll = $_POST['roll'];
$classes_attended = (int)$_POST['classes_attended'];
$total_classes = (int)$_POST['total_classes'];
$student_name = $_POST['name'];
// other vars remain same...

// Check if attendance already exists
$check = $conn->prepare("SELECT * FROM attendance WHERE student_roll = ? AND faculty = ? AND semester = ? AND section = ? AND subject = ?");
$check->bind_param("sssss", $student_roll, $faculty, $semester, $section, $subject);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Update
    $sql = "UPDATE attendance SET student_name = ?, classes_attended = ?, total_classes = ?, percentage = ? 
            WHERE student_roll = ? AND faculty = ? AND semester = ? AND section = ? AND subject = ?";
    $percentage = $total_classes > 0 ? round(($classes_attended / $total_classes) * 100, 2) : 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissssss", $student_name, $classes_attended, $total_classes, $percentage, $student_roll, $faculty, $semester, $section, $subject);
    $stmt->execute();
    echo "Updated!";
    $stmt->close();
} else {
    // Insert
    $sql = "INSERT INTO attendance (student_roll, student_name, faculty, semester, section, subject, classes_attended, total_classes, percentage)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $percentage = $total_classes > 0 ? round(($classes_attended / $total_classes) * 100, 2) : 0;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiid", $student_roll, $student_name, $faculty, $semester, $section, $subject, $classes_attended, $total_classes, $percentage);
    $stmt->execute();
    echo "Inserted!";
    $stmt->close();
}

$conn->close();
?>
