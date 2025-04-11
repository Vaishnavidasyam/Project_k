<?php
include "connect.php";

$roll = $_POST['roll'];
$attended = $_POST['attended'];
$total = $_POST['total'];

$sql = "UPDATE students SET classes_attended=?, total_classes=? WHERE roll=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $attended, $total, $roll);
$stmt->execute();
?>
