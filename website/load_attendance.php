<?php
$facultyName = $_GET['facultyName'];
$semester = $_GET['semester'];
$section = $_GET['section'];
$subject = $_GET['subject'];

// Connect to database
$conn = new mysqli("localhost", "root", "", "faculty");

if ($conn->connect_error) {
    die(json_encode(['error' => "Database connection failed"]));
}

// Query the attendance table
$sql = "SELECT SUM(classes_attended) as classes_attended, SUM(total_classes) as total_classes
        FROM attendance 
        WHERE faculty_name=? AND semester=? AND section=? AND subject=?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $facultyName, $semester, $section, $subject);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || ($data['total_classes'] == 0 && $data['classes_attended'] == 0)) {
    echo json_encode(['error' => "No data found"]);
    exit;
}

// Calculate percentage
$percentage = $data['total_classes'] > 0 ? round(($data['classes_attended'] / $data['total_classes']) * 100) : 0;

echo json_encode([
    'total_classes' => $data['total_classes'],
    'classes_attended' => $data['classes_attended'],
    'percentage' => $percentage
]);

$conn->close();
?>
