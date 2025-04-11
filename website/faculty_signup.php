<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // change if you set a password in XAMPP
$dbname = "signin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into faculty table
    $stmt = $conn->prepare("INSERT INTO faculty (name, email, department, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $department, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to login
        header("Location: staff1.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
