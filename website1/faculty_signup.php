<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // change if needed
$dbname = "signin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM faculty WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='facsignin.html';</script>";
        exit();
    }
    $checkStmt->close();

    // Insert new faculty
    $insertStmt = $conn->prepare("INSERT INTO faculty (name, email, department, password) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("ssss", $name, $email, $department, $hashedPassword);

    if ($insertStmt->execute()) {
        // Encode the name safely for URL
        $encodedName = urlencode($name);
        header("Location: staff1.html?name=$encodedName");
        exit();
    } else {
        echo "<script>alert('Something went wrong. Please try again!'); window.location.href='facsignin.html';</script>";
    }

    $insertStmt->close();
}

$conn->close();
?>
