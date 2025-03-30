<?php
include 'db_connect.php'; // Ensure this file connects to your MySQL database

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
        echo "Registration successful!";
        header("Location: faclogin.html"); // Redirect to login page
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
