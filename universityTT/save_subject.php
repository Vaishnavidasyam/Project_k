<?php 
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "subjects");

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$subject = $_POST['subjectName'] ?? '';

if (!empty($subject)) {
    $stmt = $conn->prepare("INSERT IGNORE INTO subjects (subject_name) VALUES (?)");
    $stmt->bind_param("s", $subject);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Subject saved successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Subject name is required"]);
}

$conn->close();
?>
