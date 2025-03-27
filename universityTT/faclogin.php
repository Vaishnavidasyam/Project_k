<?php
session_start();
include 'db_connect.php'; // Ensure it connects to 'university_login'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Select from the 'faculty' table (not 'faculty_users')
    $sql = "SELECT * FROM faculty WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['faculty_name'] = $row['name'];
            echo "<script>alert('Login successful!'); window.location='staff1.html';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location='faclogin.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location='faclogin.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
