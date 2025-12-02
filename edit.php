<?php

include('db_connection.php');

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

  
    $sql = "SELECT * FROM cars WHERE id = '$edit_id'";
    $result = $conn->query($sql);

    
    if ($result->num_rows > 0) {
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
        $image = $row['image'];
        $image2 = $row['image2'];
    } else {
  
        header('Location: display.php');
        exit();
    }
} else {
   
    header('Location: display.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $customer_name = $_POST['customer_name'];
    $plate = $_POST['plate'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $km_mile = $_POST['km_mile'];
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

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image;
        move_uploaded_file($image_tmp, $image_folder);
    }

    if (isset($_FILES['image2']) && $_FILES['image2']['error'] == 0) {
        $image2 = $_FILES['image2']['name'];
        $image_tmp2 = $_FILES['image2']['tmp_name'];
        $image_folder2 = 'uploads/' . $image2;
        move_uploaded_file($image_tmp2, $image_folder2);
    }

    
    $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', brand = '$brand', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense', image = '$image', image2 = '$image2' WHERE id = '$edit_id'";

    if ($conn->query($sql) === TRUE) {
       
        header('Location: display.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Car Database</h1>
    <h2>Edit Car Details</h2>
</header>

<div class="container">
    <form action="edit.php?edit_id=<?php echo $edit_id; ?>" method="post" enctype="multipart/form-data">
        <label for="customer_name">Customer Name</label>
        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" required>

        <label for="plate">Plate</label>
        <input type="text" name="plate" value="<?php echo htmlspecialchars($plate); ?>" required>

        <label for="brand">Brand</label>
        <input type="text" name="brand" value="<?php echo htmlspecialchars($brand); ?>" required>

        <label for="model">Model</label>
        <input type="text" name="model" value="<?php echo htmlspecialchars($model); ?>" required>

        <label for="km_mile">Kilometers/Miles</label>
        <input type="text" name="km_mile" value="<?php echo htmlspecialchars($km_mile); ?>" required>

        <label for="accident_visual">Accident Visual</label>
        <input type="text" name="accident_visual" value="<?php echo htmlspecialchars($accident_visual); ?>" required>

        <label for="accident_tramer">Accident Tramer</label>
        <input type="text" name="accident_tramer" value="<?php echo htmlspecialchars($accident_tramer); ?>" required>

        <label for="msf">MSF</label>
        <input type="text" name="msf" value="<?php echo htmlspecialchars($msf); ?>" required>

        <label for="dsf">DSF</label>
        <input type="text" name="dsf" value="<?php echo htmlspecialchars($dsf); ?>" required>

        <label for="package">Package</label>
        <input type="text" name="package" value="<?php echo htmlspecialchars($package); ?>" required>

        <label for="color">Color</label>
        <input type="text" name="color" value="<?php echo htmlspecialchars($color); ?>" required>

        <label for="engine">Engine</label>
        <input type="text" name="engine" value="<?php echo htmlspecialchars($engine); ?>" required>

        <label for="gear">Gear</label>
        <input type="text" name="gear" value="<?php echo htmlspecialchars($gear); ?>" required>

        <label for="fuel">Fuel</label>
        <input type="text" name="fuel" value="<?php echo htmlspecialchars($fuel); ?>" required>

        <label for="expense_detail">Expense Details</label>
        <textarea name="expense_detail" required><?php echo htmlspecialchars($expense_detail); ?></textarea>

        <label for="current_expense">Current Expense</label>
        <input type="text" name="current_expense" value="<?php echo htmlspecialchars($current_expense); ?>" required>

        <label for="image">Image</label>
        <input type="file" name="image">

        <label for="image2">Second Image</label>
        <input type="file" name="image2">

        <button type="submit">Update Car</button>
    </form>
</div>

<div class="action-links">
    <a href="display.php">View All Cars</a>
    <a href="home.php">Back to Home</a>
</div>

<footer>
    <p>&copy; 2025 Car Database System. All rights reserved.</p>
</footer>

</body>
</html>
