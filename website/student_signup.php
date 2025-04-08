<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $rollnumber = $_POST['rollnumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if roll number or email already exists
    $check_query = "SELECT * FROM students WHERE rollnumber = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $rollnumber, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Roll number or email already registered.'); window.location.href='stusignin.html';</script>";
    } else {
        // Insert new student record
        $sql = "INSERT INTO students (name, rollnumber, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $rollnumber, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Redirecting to login...'); window.location.href='stuTT.html';</script>";
        } else {
            echo "<script>alert('Error signing up. Try again.'); window.location.href='stusignin.html';</script>";
        }
    }
}
?>
