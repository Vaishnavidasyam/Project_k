<?php
$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name     = trim($_POST['name']);
$roll     = trim(strtolower($_POST['roll']));
$semester = trim(strtolower($_POST['semester']));
$section  = trim(strtolower($_POST['section']));
$subject  = trim(strtolower($_POST['subject']));
$faculty  = trim(strtolower($_POST['faculty']));

// Optional: Debug query
$checkQuery = "SELECT * FROM students 
WHERE LOWER(TRIM(roll)) = '$roll' 
AND LOWER(TRIM(semester)) = '$semester'
AND LOWER(TRIM(section)) = '$section'
AND LOWER(TRIM(subject)) = '$subject'
AND LOWER(TRIM(faculty)) = '$faculty'";

$result = $conn->query($checkQuery);

if ($result && $result->num_rows > 0) {
    echo "<script>alert('Student already exists in this class and subject.'); window.history.back();</script>";
    exit();
}

// GOOD ✅
$sql = "INSERT INTO students (name, roll, semester, section, subject, faculty, classes_attended, total_classes) 
        VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
$stmt->bind_param("ssssss", $name, $roll, $semester, $section, $subject, $faculty);

try {
    $stmt->execute();
    echo "<script>alert('Student added successfully!'); window.location.href='staff4.html';</script>";
} catch (mysqli_sql_exception $e) {
    echo "Database error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
