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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subjectName"])) {
    $subjectName = trim($_POST["subjectName"]);
    $semester = $_POST["semester"] ?? '';
    $section = $_POST["section"] ?? '';

    if (!empty($subjectName) && !empty($semester) && !empty($section)) {
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name, semester, section) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $subjectName, $semester, $section);

        if ($stmt->execute()) {
            echo "Subject '$subjectName' added successfully for $semester - $section!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Subject name, semester and section are required!";
    }
}

$conn->close();
?>
