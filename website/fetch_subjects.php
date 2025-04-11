<?php
$connection = new mysqli("localhost", "root", "", "hod");

$semester = $_GET['semester'];

$query = $connection->prepare("SELECT DISTINCT subject_name FROM subjects WHERE semester = ?");
$query->bind_param("s", $semester);
$query->execute();

$result = $query->get_result();
$subjects = [];

while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject_name'];
}

echo json_encode($subjects);

$query->close();
$connection->close();
?>
