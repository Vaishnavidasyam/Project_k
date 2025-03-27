<?php
$conn = new mysqli('localhost', 'root', '', 'university_tt');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the frontend
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['timetableData'])) {
    $stmt = $conn->prepare("INSERT INTO hodtt (day, time, subject, faculty) VALUES (?, ?, ?, ?)");

    foreach ($data['timetableData'] as $entry) {
        $stmt->bind_param(
            "ssss",
            $entry['day'],
            $entry['time'],
            $entry['subject'],
            $entry['faculty']
        );
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo "Timetable saved successfully!";
} else {
    echo "Failed to save timetable.";
}
?>
