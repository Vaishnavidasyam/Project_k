<?php
session_start();
session_destroy();
header("Location: first.html"); // Redirect to the main page
exit();
?>
