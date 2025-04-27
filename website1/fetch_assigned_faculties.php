<?php 
// Database credentials
$servername = "localhost";
$username = "root"; // Adjust with your database username
$password = ""; // Adjust with your database password
$dbname = "hod"; 

// PDO options
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

try {
    // Establish PDO connection
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4"; // Set charset to avoid issues with multibyte characters
    $pdo = new PDO($dsn, $username, $password, $options);

    // Get POST data (from JSON request)
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract data from the received JSON
    $semester = $data['semester'];
    $classValue = $data['classValue'];
    $department = $data['department'];
    $room = $data['room'];
    $assignments = $data['assignments'];

    // Create an array to store error messages
    $errors = [];

    // Create an array to store all faculties that need to be checked
    $faculties = [];
    
    // Loop through each assignment to gather faculties
    foreach ($assignments as $assignment) {
        foreach ($assignment['faculties'] as $faculty) {
            $faculties[] = $faculty;
        }
    }
    
    // Ensure that faculty list is unique (no duplicates)
    $faculties = array_unique($faculties);
    
    // Prepare the query to check all faculties in a single call
    $placeholders = str_repeat('?,', count($faculties) - 1) . '?';
    
    // Prepare and execute the query to check if any faculty is already assigned in the given context
    $stmt = $pdo->prepare(
        "SELECT * FROM subfac 
        WHERE semester = ? 
        AND class = ? 
        AND department = ? 
        AND room = ? 
        AND faculty IN ($placeholders)"
    );
    
    $stmt->execute(array_merge([$semester, $classValue, $department, $room], $faculties));

    // Check if any rows are returned (meaning there's a conflict)
    if ($stmt->rowCount() > 0) {
        // If there are conflicts, we loop through each returned row to generate error messages
        while ($row = $stmt->fetch()) {
            $conflictingFaculty = $row['faculty'];
            $errors[] = "Faculty '$conflictingFaculty' is already assigned to another subject in the class '$classValue' for semester '$semester' in room '$room'.";
        }
    }

    // Return errors if any, otherwise return success
    if (count($errors) > 0) {
        echo json_encode(['error' => implode(', ', $errors)]);
    } else {
        // Success response, no conflicts found
        echo json_encode(['success' => 'No conflicts detected']);
    }

} catch (PDOException $e) {
    // Catch any PDO exception and return a detailed error message
    echo json_encode([
        'error' => 'Connection failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine()
    ]);
}
?>
