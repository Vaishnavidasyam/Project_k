<?php
include 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Secure password

    // Check if email already exists
    $checkQuery = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists!";
        exit();
    }

    // Insert into database
    $query = "INSERT INTO admin (name, email, department, password) VALUES ('$name', '$email', '$department', '$password')";
    if (mysqli_query($conn, $query)) {
        header("Location: hodlog.html"); // Redirect to login after successful signup
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
