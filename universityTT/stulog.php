<?php
$conn = new mysqli('localhost', 'root', '', 'university_signdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rollnumber = $_POST['rollnumber'];
$password = $_POST['password'];

$sql = "SELECT * FROM student WHERE rollnumber=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $rollnumber);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user'] = $user['name'];
    header('Location: stuTT.html');
} else {
    echo "Invalid credentials!";
}

$stmt->close();
$conn->close();
?>
