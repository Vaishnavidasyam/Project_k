<?php
// Da// Database connection parameters
$host = 'localhost'; // Adjust accordingly if needed
$dbname = 'hod';     // Database name (replace with your actual database name)
$username = 'root';  // Your DB username
$password = '';      // Your DB password (update if needed)

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

foreach ($data as $assignment) {
    $subject = $assignment->subject;
    $faculty = $assignment->faculty;
    $semester = $assignment->semester;
    $class = $assignment->class;
    $department = $assignment->department;
    $room = $assignment->room;

    // Check if the assignment already exists in the database
    $query = "SELECT id FROM subfac WHERE subject = :subject AND semester = :semester AND class = :class AND department = :department AND room = :room";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':subject' => $subject,
        ':semester' => $semester,
        ':class' => $class,
        ':department' => $department,
        ':room' => $room,
    ]);
    $existingAssignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingAssignment) {
        // Update existing assignment if found
        $updateQuery = "UPDATE subfac SET faculty = :faculty WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ':faculty' => $faculty,
            ':id' => $existingAssignment['id']
        ]);
    } else {
        // Insert new assignment if no duplicates
        $insertQuery = "INSERT INTO subfac (subject, faculty, semester, class, department, room) VALUES (:subject, :faculty, :semester, :class, :department, :room)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            ':subject' => $subject,
            ':faculty' => $faculty,
            ':semester' => $semester,
            ':class' => $class,
            ':department' => $department,
            ':room' => $room,
        ]);
    }
}

// Respond with success message
echo json_encode(['success' => true]);
 ?>
