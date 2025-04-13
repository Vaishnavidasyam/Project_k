<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollnumber = $_POST['rollnumber'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE rollnumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollnumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['rollnumber'] = $row['rollnumber'];
            $_SESSION['name'] = $row['name'];

            // Redirect with query params
            $name = urlencode($row['name']);
            $roll = urlencode($row['rollnumber']);
            echo "<script>
              alert('Login successful!');
              window.location.href = 'stu1.html?name={$name}&rollnumber={$roll}';
            </script>";
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='stulogin.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this roll number!'); window.location.href='stulogin.html';</script>";
    }
}
?>
