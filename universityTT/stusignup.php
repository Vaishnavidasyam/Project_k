<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $rollnumber = $_POST['rollnumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO students (name, rollnumber, email, password) VALUES ('$name', '$rollnumber', '$email', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "Student registered successfully!";
        header("Location: stuTT.html");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
