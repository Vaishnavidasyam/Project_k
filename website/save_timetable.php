<?php
$host = "localhost";
$username = "root";
$password = ""; // Change if needed
$database = "hod";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$semester = $conn->real_escape_string($data["semester"]);
$section = $conn->real_escape_string($data["section"]);
$timetable = $data["timetable"];
$department = $conn->real_escape_string($data["department"]);

// Inserted, updated, deleted counters
$inserted = 0;
$updated = 0;
$deleted = 0;

// Fetch current timetable for the given semester, section, and department
$existingTimetableQuery = "SELECT * FROM tt WHERE semester='$semester' AND section='$section' AND department='$department'";
$existingTimetableResult = $conn->query($existingTimetableQuery);
$existingTimetable = [];

while ($row = $existingTimetableResult->fetch_assoc()) {
    $existingTimetable[] = $row;
}

// Step 1: Find classes to delete (those that are no longer in the new timetable)
$deletedClasses = [];
foreach ($existingTimetable as $existing) {
    $existsInNewTimetable = false;
    foreach ($timetable as $entry) {
        if ($existing["day"] == $entry["day"] &&
            $existing["time"] == $entry["time"] &&
            $existing["subject"] == $entry["subject"] &&
            $existing["faculty"] == $entry["faculty"] &&
            $existing["room"] == $entry["room"]) {
            $existsInNewTimetable = true;
            break;
        }
    }

    if (!$existsInNewTimetable) {
        // This entry is deleted
        $deletedClasses[] = $existing["id"]; // assuming `id` is the primary key of the `tt` table
    }
}

// Step 2: Delete records that are no longer in the new timetable
if (count($deletedClasses) > 0) {
    $deletedClassesList = implode(',', $deletedClasses);
    $deleteQuery = "DELETE FROM tt WHERE id IN ($deletedClassesList)";
    $conn->query($deleteQuery);
    $deleted = $conn->affected_rows; // Capture the number of rows deleted
}

// Step 3: Insert new records and update existing ones
foreach ($timetable as $entry) {
    $day = $conn->real_escape_string($entry["day"]);
    $time = $conn->real_escape_string($entry["time"]);
    $subject = $conn->real_escape_string($entry["subject"]);
    $faculty = $conn->real_escape_string($entry["faculty"]);
    $room = $conn->real_escape_string($entry["room"]);

    // Check if the entry already exists
    $checkQuery = "SELECT * FROM tt WHERE semester='$semester' AND section='$section' AND department='$department' AND day='$day' AND time='$time' AND subject='$subject' AND faculty='$faculty' AND room='$room'";
    $result = $conn->query($checkQuery);

    if ($result && $result->num_rows == 0) {
        // Insert new record if it doesn't exist
        $insertQuery = "INSERT INTO tt (semester, section, department, day, time, subject, faculty, room)
                        VALUES ('$semester', '$section', '$department', '$day', '$time', '$subject', '$faculty', '$room')";

        if ($conn->query($insertQuery)) {
            $inserted++;
        }
    } else {
        // Update existing record if it exists (optional, based on your requirements)
        $updateQuery = "UPDATE tt SET subject='$subject', faculty='$faculty', room='$room'
                        WHERE semester='$semester' AND section='$section' AND department='$department' 
                        AND day='$day' AND time='$time'";

        if ($conn->query($updateQuery)) {
            $updated++;
        }
    }
}

// Return the result
echo "Inserted: $inserted, Updated: $updated, Deleted: $deleted";

$conn->close();
?>
