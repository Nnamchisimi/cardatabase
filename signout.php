<?php
session_start();  // Start the session

// Destroy all session variables to log the user out
session_unset();
session_destroy();

// Redirect to the login page after signing out
header("Location: login.php");
exit();
?>
