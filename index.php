<?php
include('db_connection.php');

// Check if we are editing an existing car
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch the car's data based on the edit_id
    $sql = "SELECT * FROM cars WHERE id = '$edit_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the row data
        $row = $result->fetch_assoc();
        $customer_name = $row['customer_name'];
        $plate = $row['plate'];
        $brand = $row['brand'];
        $model = $row['model'];
        $km_mile = $row['km_mile'];
        $accident_visual = $row['accident_visual'];
        $accident_tramer = $row['accident_tramer'];
        $msf = $row['msf'];
        $dsf = $row['dsf'];
        $package = $row['package'];
        $color = $row['color'];
        $engine = $row['engine'];
        $gear = $row['gear'];
        $fuel = $row['fuel'];
        $expense_detail = $row['expense_detail'];
        $current_expense = $row['current_total_expense'];
    }
} else {
    // If not editing, set empty values
    $customer_name = $plate = $brand = $model = $km_mile = $accident_visual = $accident_tramer = $msf = $dsf = $package = $color = $engine = $gear = $fuel = $expense_detail = $current_expense = '';
}

// Check if form is submitted for adding or editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $customer_name = $_POST['name'];
    $plate = $_POST['plate'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $km_mile = $_POST['mile'];
    $accident_visual = $_POST['accident_visual'];
    $accident_tramer = $_POST['accident_tramer'];
    $msf = $_POST['msf'];
    $dsf = $_POST['dsf'];
    $package = $_POST['package'];
    $color = $_POST['color'];
    $engine = $_POST['engine'];
    $gear = $_POST['gear'];
    $fuel = $_POST['fuel'];
    $expense_detail = $_POST['expense_detail'];
    $current_expense = $_POST['current_expense'];

    if (isset($_GET['edit_id'])) {
        // Update the record
        $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', brand = '$brand', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense' WHERE id = '$edit_id'";

        if ($conn->query($sql) === TRUE) {
            // Redirect back to display.php after update
            header('Location: display.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert a new record
        $sql = "INSERT INTO cars (customer_name, plate, brand, model, km_mile, accident_visual, accident_tramer, msf, dsf, package, color, engine, gear, fuel, expense_detail, current_total_expense) 
        VALUES ('$customer_name', '$plate', '$brand', '$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to display.php after successful insertion
            header('Location: display.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Database</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Car Database</h1>
    <h2><?php echo isset($_GET['edit_id']) ? 'Edit Car' : 'Add New Car'; ?></h2>
    <form action="index.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>" method="post">
        <label for="name">Customer Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $customer_name; ?>" required><br><br>

        <label for="plate">Plate:</label><br>
        <input type="text" id="plate" name="plate" value="<?php echo $plate; ?>" required><br><br>

        <label for="brand">Brand:</label><br>
        <input type="text" id="brand" name="brand" value="<?php echo $brand; ?>" required><br><br>

        <label for="model">Model:</label><br>
        <input type="text" id="model" name="model" value="<?php echo $model; ?>" required><br><br>

        <label for="mile">Mile/KM:</label><br>
        <input type="text" id="mile" name="mile" value="<?php echo $km_mile; ?>" required><br><br>

        <label for="accident_visual">Accident Visual:</label><br>
        <input type="text" id="accident_visual" name="accident_visual" value="<?php echo $accident_visual; ?>" required><br><br>

        <label for="accident_tramer">Accident Tramer:</label><br>
        <input type="text" id="accident_tramer" name="accident_tramer" value="<?php echo $accident_tramer; ?>" required><br><br>

        <label for="msf">MSF:</label><br>
        <input type="text" id="msf" name="msf" value="<?php echo $msf; ?>" required><br><br>

        <label for="dsf">DSF:</label><br>
        <input type="text" id="dsf" name="dsf" value="<?php echo $dsf; ?>" required><br><br>

        <label for="package">Package:</label><br>
        <input type="text" id="package" name="package" value="<?php echo $package; ?>" required><br><br>

        <label for="color">Color:</label><br>
        <input type="text" id="color" name="color" value="<?php echo $color; ?>" required><br><br>

        <label for="engine">Engine:</label><br>
        <input type="text" id="engine" name="engine" value="<?php echo $engine; ?>" required><br><br>

        <label for="gear">Gear:</label><br>
        <input type="text" id="gear" name="gear" value="<?php echo $gear; ?>" required><br><br>

        <label for="fuel">Fuel:</label><br>
        <input type="text" id="fuel" name="fuel" value="<?php echo $fuel; ?>" required><br><br>

        <label for="expense_detail">Expense Detail:</label><br>
        <input type="text" id="expense_detail" name="expense_detail" value="<?php echo $expense_detail; ?>" required><br><br>

        <label for="current_expense">Current Total Expense:</label><br>
        <input type="text" id="current_expense" name="current_expense" value="<?php echo $current_expense; ?>" required><br><br>

        <input type="submit" value="Submit">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
