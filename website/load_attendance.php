<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the 'faculty' database directly
$conn = new mysqli("localhost", "root", "", "faculty");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['facultyName'], $_GET['semester'], $_GET['section'], $_GET['subject'])) {
  $faculty = $_GET['facultyName'];
  $semester = $_GET['semester'];
  $section = $_GET['section'];
  $subject = $_GET['subject'];

  // Get student data from students table
  $query = "SELECT * FROM students WHERE faculty = ? AND semester = ? AND section = ? AND subject = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssss", $faculty, $semester, $section, $subject);
  $stmt->execute();
  $result = $stmt->get_result();

  $students = [];
  while ($row = $result->fetch_assoc()) {
    $roll = $row['roll'];

    // Get attendance data
    $attendanceQuery = "SELECT classes_attended, total_classes FROM attendance WHERE roll = ? AND faculty = ? AND semester = ? AND section = ? AND subject = ?";
    $attendanceStmt = $conn->prepare($attendanceQuery);
    $attendanceStmt->bind_param("sssss", $roll, $faculty, $semester, $section, $subject);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();
    $attendanceData = $attendanceResult->fetch_assoc();

    $classes_attended = $attendanceData ? $attendanceData['classes_attended'] : 0;
    $total_classes = $attendanceData ? $attendanceData['total_classes'] : 0;
    $percentage = ($total_classes > 0) ? round(($classes_attended / $total_classes) * 100) : 0;

    $row['classes_attended'] = $classes_attended;
    $row['total_classes'] = $total_classes;
    $row['percentage'] = $percentage;

    $students[] = $row;
  }

  echo json_encode($students);
} else {
  echo json_encode(["error" => "Missing GET parameters."]);
}
?>
