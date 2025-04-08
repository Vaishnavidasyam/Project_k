<?php
include 'db_connect.php';

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
            session_start();
            $_SESSION['faculty_id'] = $id;
            header("Location: staff1.html"); // Redirect to dashboard
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "No account found.";
    }

    $stmt->close();
    $conn->close();
}
?>
