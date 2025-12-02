<?php
include('db_connection.php');


$search_query = '';


if (isset($_POST['search'])) {
   
    $search_query = $_POST['search'];
    
    header("Location: display.php?search=$search_query");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cars</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Search for Cars</h1>

        <!-- Search Form -->
        <form method="POST" action="search.php">
            <div class="form-group">
                <label for="search">Search by Customer Name: </label>
                <input type="text" name="search" id="search" class="input-field" placeholder="Enter customer name">
            </div>
            <div class="form-group">
                <input type="submit" value="Search" class="btn btn-primary">
            </div>
        </form>

        <a href="display.php" class="btn btn-secondary">Back to Car List</a>
        <a href="delete.php?id=5">Delete</a>

    </div>
</body>
</html>
