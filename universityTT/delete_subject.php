<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$database = "university_TT";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["day"], $data["time"], $data["subject"], $data["faculty"])) {
    echo json_encode(["error" => "Missing required parameters"]);
    exit;
}

$day = $data["day"];
$time = $data["time"];
$subject = $data["subject"];
$faculty = $data["faculty"];

$stmt = $conn->prepare("DELETE FROM hodtt WHERE day = ? AND time = ? AND subject = ? AND faculty = ?");
$stmt->bind_param("ssss", $day, $time, $subject, $faculty);

if ($stmt->execute()) {
    echo json_encode(["success" => "Subject deleted successfully"]);
} else {
    echo json_encode(["error" => "Error deleting subject"]);
}

$stmt->close();
$conn->close();
?>
