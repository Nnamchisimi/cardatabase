<?php
include('db_connection.php');

// Check if id is provided to delete the car
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and bind the SQL statement
    $delete_sql = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $delete_sql->bind_param("i", $id); // "i" indicates the parameter is an integer

    if ($delete_sql->execute()) {
        // Redirect to display page after successful deletion
        header("Location: display.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the prepared statement
    $delete_sql->close();
}

// Close the connection
$conn->close();
?>
