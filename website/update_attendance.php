<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "faculty";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// If the data comes from saveAttendance (JSON payload)
if ($_SERVER["CONTENT_TYPE"] === "application/json") {
  $input = json_decode(file_get_contents("php://input"), true);

  foreach ($input['updates'] as $update) {
    $roll = $update['roll'];
    $attended = (int)$update['attended'];
    $total = (int)$update['total'];
    $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;

    $stmt = $conn->prepare("UPDATE students SET classes_attended=?, total_classes=?, percentage=? WHERE roll=?");
    $stmt->bind_param("iiis", $attended, $total, $percentage, $roll);
    $stmt->execute();
    $stmt->close();
  }
  echo "Bulk attendance updated.";
} else {
  // For individual adjustments via adjustStoredCount (form-urlencoded)
  $roll = $_POST['roll'];
  $attended = (int)$_POST['attended'];
  $total = (int)$_POST['total'];
  $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;

  $stmt = $conn->prepare("UPDATE students SET classes_attended=?, total_classes=?, percentage=? WHERE roll=?");
  $stmt->bind_param("iiis", $attended, $total, $percentage, $roll);
  $stmt->execute();
  $stmt->close();

  echo "Attendance updated.";
}

$conn->close();
?>
