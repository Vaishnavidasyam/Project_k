<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rollnumber = $conn->real_escape_string($_POST['rollnumber']);
    $password = $_POST['password'];

    // Check if roll number exists
    $query = "SELECT * FROM students WHERE rollnumber = '$rollnumber'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['rollnumber'] = $rollnumber; // Store session
            header("Location: stuTT.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location='stulogin.html';</script>";
        }
    } else {
        echo "<script>alert('No account found!'); window.location='stulogin.html';</script>";
    }
}

$conn->close();
?>
