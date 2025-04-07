<?php
include 'db_connect.php'; // Database connection

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if email exists
    $query = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row["password"])) {
            $_SESSION["admin"] = $row["name"];
            header("Location: hodlog.html"); // Redirect to dashboard
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with this email!";
    }
}
?>
