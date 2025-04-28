<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hod"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $entry) {
    $day = $conn->real_escape_string($entry['day']);
    $time = $conn->real_escape_string($entry['time']);
    $subject = $conn->real_escape_string($entry['subject']);
    $twoSections = $entry['twoSections'] ? 1 : 0;
    $semester = $conn->real_escape_string($entry['semester']);
    $class = $conn->real_escape_string($entry['class']);
    $room = $conn->real_escape_string($entry['room']);
    $department = $conn->real_escape_string($entry['department']);

    // 1. Get faculty assigned to this subject for this semester/class/room/department
    $facultyQuery = "SELECT faculty FROM subfac 
                     WHERE subject='$subject' 
                       AND semester='$semester' 
                       AND class='$class' 
                       AND room='$room' 
                       AND department='$department'";

    $facultyResult = $conn->query($facultyQuery);

    if ($facultyResult->num_rows == 0) {
        echo "Faculty not assigned to this subject.";
        exit;
    }

    $facultyRow = $facultyResult->fetch_assoc();
    $faculty = $conn->real_escape_string($facultyRow['faculty']);

    // 2. Now check if this faculty is already assigned in timetable at this day and time
    $conflictQuery = "SELECT timetable.semester, timetable.class, timetable.room, timetable.day, timetable.time_slot 
                      FROM timetable 
                      INNER JOIN subfac 
                      ON timetable.subject = subfac.subject 
                         AND timetable.semester = subfac.semester 
                         AND timetable.class = subfac.class 
                         AND timetable.room = subfac.room 
                         AND timetable.department = subfac.department 
                      WHERE subfac.faculty = '$faculty' 
                        AND timetable.day = '$day' 
                        AND timetable.time_slot = '$time'";

    $conflictResult = $conn->query($conflictQuery);

    if ($conflictResult->num_rows > 0) {
        $conflictRow = $conflictResult->fetch_assoc();
        $assignedSemester = $conflictRow['semester'];
        $assignedClass = $conflictRow['class'];
        $assignedRoom = $conflictRow['room'];
        $assignedDay = $conflictRow['day'];
        $assignedTime = $conflictRow['time_slot'];

        echo "Faculty $faculty is already assigned to $assignedSemester semester, $assignedClass class, $assignedRoom room at $assignedTime on $assignedDay.";
        exit;
    }

    // 3. Now insert or update timetable
    $checkSql = "SELECT * FROM timetable 
                 WHERE day='$day' 
                   AND time_slot='$time' 
                   AND semester='$semester' 
                   AND class='$class' 
                   AND room='$room' 
                   AND department='$department'";

    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Update
        $updateSql = "UPDATE timetable 
                      SET subject='$subject', two_sections='$twoSections'
                      WHERE day='$day' 
                        AND time_slot='$time' 
                        AND semester='$semester' 
                        AND class='$class' 
                        AND room='$room' 
                        AND department='$department'";
        $conn->query($updateSql);
    } else {
        // Insert
        $insertSql = "INSERT INTO timetable 
                      (day, time_slot, subject, two_sections, semester, class, room, department) 
                      VALUES 
                      ('$day', '$time', '$subject', '$twoSections', '$semester', '$class', '$room', '$department')";
        $conn->query($insertSql);
    }
}

$conn->close();
echo "Success";
?>
