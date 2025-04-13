<?php
$conn = new mysqli("localhost", "root", "", "hod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$subject_name = $_POST['subject_name'] ?? '';
$semester = $_POST['semester'] ?? '';
$section = $_POST['section'] ?? '';

if (empty($subject_name) || empty($semester) || empty($section)) {
    echo "Missing input values.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO subjects (subject_name, semester, section) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $subject_name, $semester, $section);

if ($stmt->execute()) {
    echo "Subject added successfully!";
} else {
    echo "Failed to add subject: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
