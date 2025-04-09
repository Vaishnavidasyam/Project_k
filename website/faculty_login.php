<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // update if you set a password
$dbname = "signin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user from database
    $stmt = $conn->prepare("SELECT id, password FROM faculty WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['faculty_id'] = $id;
            header("Location: staff1.html"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "No account found.";
    }

    $stmt->close();
}

$conn->close();
?>
