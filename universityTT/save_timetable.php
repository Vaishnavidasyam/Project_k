<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "subjects");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the subject name from the request
$subject = $_POST['subjectName'] ?? '';

// Insert the subject into the "subjects" table
if (!empty($subject)) {
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
    $stmt->bind_param("s", $subject);

    if ($stmt->execute()) {
        echo "Subject saved successfully";
    } else {
        echo "Error saving subject: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
