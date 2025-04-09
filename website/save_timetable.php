<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hod";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$semester = $conn->real_escape_string($data["semester"]);
$section = $conn->real_escape_string($data["section"]);
$timetable = $data["timetable"];

foreach ($timetable as $entry) {
  $day = $conn->real_escape_string($entry["day"]);
  $time = $conn->real_escape_string($entry["time"]);
  $subject = $conn->real_escape_string($entry["subject"]);
  $faculty = $conn->real_escape_string($entry["faculty"]);
  $room = $conn->real_escape_string($entry["room"]);

  $sql = "INSERT INTO tt (semester, section, day, time, subject, faculty, room)
          VALUES ('$semester', '$section', '$day', '$time', '$subject', '$faculty', '$room')";
  $conn->query($sql);
}

$conn->close();
echo "Timetable saved successfully!";
?>
