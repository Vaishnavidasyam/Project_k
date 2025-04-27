<?php
// Get POST data
$roll = $_POST['roll'] ?? null;
$semester = $_POST['semester'] ?? null;
$faculty = $_POST['faculty'] ?? null;
$subject = $_POST['subject'] ?? null;

// Validate inputs
if (!$roll || !$semester || !$faculty || !$subject) {
    echo "Missing required parameters.";
    exit();
}

// Create connection to the database
$conn = new mysqli("localhost", "root", "", "faculty");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Begin transaction to ensure both deletions happen atomically
$conn->begin_transaction();

try {
    // Prepare the delete statement for the students table
    $stmt1 = $conn->prepare("DELETE FROM students WHERE roll = ? AND semester = ? AND subject = ? AND faculty = ?");
    $stmt1->bind_param("ssss", $roll, $semester, $subject, $faculty);
    $stmt1->execute();

    // Prepare the delete statement for the attendance table
    $stmt2 = $conn->prepare("DELETE FROM attendance WHERE roll = ? AND semester = ? AND subject = ? AND faculty = ?");
    $stmt2->bind_param("ssss", $roll, $semester, $subject, $faculty);
    $stmt2->execute();

    // Commit transaction if both deletions were successful
    if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
        $conn->commit();
        echo "Deleted successfully.";
    } else {
        $conn->rollback();
        echo "No matching records found.";
    }
} catch (Exception $e) {
    // Rollback in case of any error
    $conn->rollback();
    echo "Error deleting records: " . $e->getMessage();
}

// Close the statements and connection
$stmt1->close();
$stmt2->close();
$conn->close();
?>
