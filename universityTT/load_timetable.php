<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_TT";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT day, time, subject, faculty FROM hodtt";
$result = $conn->query($sql);

$timetable = [];
$subjects = [];
$faculty = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = [
            "day" => strtoupper($row["day"]),
            "time" => $row["time"],
            "subject" => $row["subject"],
            "faculty" => $row["faculty"]
        ];

        if (!in_array($row["subject"], $subjects)) {
            $subjects[] = $row["subject"];
        }

        if (!in_array($row["faculty"], $faculty)) {
            $faculty[] = $row["faculty"];
        }
    }
}

$conn->close();

echo json_encode([
    "timetable" => $timetable,
    "subjects" => $subjects,
    "faculty" => $faculty
]);
?>
