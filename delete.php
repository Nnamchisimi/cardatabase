<?php
include('db_connection.php');


if (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $delete_sql = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $delete_sql->bind_param("i", $id); 

    if ($delete_sql->execute()) {

        header("Location: display.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $delete_sql->close();
}


$conn->close();
?>
