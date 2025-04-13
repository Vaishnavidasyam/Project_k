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

$inserted = 0;
$skipped = 0;

foreach ($timetable as $entry) {
  $day = $conn->real_escape_string($entry["day"]);
  $time = $conn->real_escape_string($entry["time"]);
  $subject = $conn->real_escape_string($entry["subject"]);
  $faculty = $conn->real_escape_string($entry["faculty"]);
  $room = $conn->real_escape_string($entry["room"]);

  // Check if entry already exists
  $checkQuery = "SELECT * FROM tt WHERE semester='$semester' AND section='$section' AND day='$day' AND time='$time' AND subject='$subject' AND faculty='$faculty' AND room='$room'";
  $result = $conn->query($checkQuery);

  if ($result && $result->num_rows == 0) {
    // Insert new record
    $insertQuery = "INSERT INTO tt (semester, section, day, time, subject, faculty, room)
    VALUES ('$semester', '$section', '$day', '$time', '$subject', '$faculty', '$room')";

if ($conn->query($insertQuery)) {
$inserted++;
}
} else {
$skipped++;
}
}

echo "Inserted: $inserted, Skipped: $skipped";

$conn->close();
?>
