<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO admins (name, email, department, password) VALUES ('$name', '$email', '$department', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "Admin registered successfully!";
        header("Location: hodlog.html");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
