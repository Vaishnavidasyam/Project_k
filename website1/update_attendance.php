<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "hod";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Trim input for safety
    $name = trim($_POST['name']);
    $roll = trim($_POST['roll']);
    $attendance = trim($_POST['attendance']);
    $faculty = trim($_POST['faculty']);
    $semester = trim($_POST['semester']);
    $class = trim($_POST['class']);
    $subject = trim($_POST['subject']);
    $date = trim($_POST['date']);

    // Check if attendance already exists
    $check = $conn->prepare("SELECT * FROM attendance WHERE roll_no = ? AND date = ? AND subject = ?");
    $check->bind_param("sss", $roll, $date, $subject);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE attendance SET attendance_status = ?, faculty = ?, semester = ?, class = ?, student_name = ? WHERE roll_no = ? AND date = ? AND subject = ?");
        $stmt->bind_param("ssssssss", $attendance, $faculty, $semester, $class, $name, $roll, $date, $subject);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO attendance (student_name, roll_no, attendance_status, date, subject, faculty, semester, class) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $roll, $attendance, $date, $subject, $faculty, $semester, $class);
    }

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
