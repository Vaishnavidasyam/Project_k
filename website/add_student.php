<?php
$conn = new mysqli("localhost", "root", "", "faculty");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data and sanitize
$name = trim($_POST['name']);
$roll = trim($_POST['roll']);
$semester = trim($_POST['semester']);
$section = trim($_POST['section']);
$subject = trim($_POST['subject']);
$faculty = trim($_POST['faculty']);

// Check if student with same roll number, subject, and semester already exists
$check = $conn->prepare("SELECT * FROM students WHERE roll = ? AND subject = ? AND semester = ?");
$check->bind_param("sss", $roll, $subject, $semester);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Student already exists!'); window.history.back();</script>";
    exit();
}
$check->close();

// Prepare and execute SQL insert query
$stmt = $conn->prepare("INSERT INTO students (name, roll, semester, section, subject, faculty, classes_attended, total_classes) VALUES (?, ?, ?, ?, ?, ?, 0, 0)");
$stmt->bind_param("ssssss", $name, $roll, $semester, $section, $subject, $faculty);

// Execute the statement
// Execute the statement
if ($stmt->execute()) {
  echo "<script>
      alert('Student added successfully!');
      window.location.href = 'staff3.html?semester=" . urlencode($semester) . "&section=" . urlencode($section) . "&subject=" . urlencode($subject) . "&name=" . urlencode($faculty) . "';
  </script>";
} else {
  echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
