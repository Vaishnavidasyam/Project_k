<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod"; // your database

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $entry) {
  $day = $conn->real_escape_string($entry['day']);
  $time = $conn->real_escape_string($entry['time']);
  $subject = $conn->real_escape_string($entry['subject']);
  $twoSections = $entry['twoSections'] ? 1 : 0;
  $semester = $conn->real_escape_string($entry['semester']);
  $class = $conn->real_escape_string($entry['class']);
  $room = $conn->real_escape_string($entry['room']);
  $department = $conn->real_escape_string($entry['department']);

  $sql = "INSERT INTO timetable 
            (day, time_slot, subject, two_sections, semester, class, room, department)
          VALUES 
            ('$day', '$time', '$subject', '$twoSections', '$semester', '$class', '$room', '$department')";

  $conn->query($sql);
}

$conn->close();
echo "Success";
?>
