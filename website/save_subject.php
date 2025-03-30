<?php
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";      // Change if necessary
$database = "hod";   // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get subject name from POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subjectName"])) {
    $subjectName = trim($_POST["subjectName"]);

    if (!empty($subjectName)) {
        // Prepare SQL query to insert subject
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
        $stmt->bind_param("s", $subjectName);

        if ($stmt->execute()) {
            echo "Subject '$subjectName' added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Subject name cannot be empty!";
    }
}

$conn->close();
?>
