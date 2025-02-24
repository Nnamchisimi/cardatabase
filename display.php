<?php
include('db_connection.php');

// Initialize variables
$search_query = '';


// Check if the search query is set in the URL
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);  // sanitize input
    $sql = "SELECT * FROM cars WHERE customer_name LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM cars";
}

$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Error in query execution: " . $conn->error);
}

// Handle the unit and value form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected unit and entered value
    $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
    $value = isset($_POST['value']) ? $_POST['value'] : '';

    // Handle form submission
    if ($unit && $value) {
        // Example: Insert data into database
        $sql = "INSERT INTO cars (km_mile, unit) VALUES ('$value', '$unit')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Data successfully inserted!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "Please provide both a value and unit!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Part Details Result</title>
    <link rel="icon" href="https://kombosapp.pythonanywhere.com/static/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
            background-color: #000000;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .header-text {
            font-size: 2rem;
            font-weight: bold;
            color: #e0d8d8;
        }

        .no-details {
            color: #000000;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #101111;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            max-width: 200px;
            width: auto;
        }

        .back-button:hover {
            background-color: #a5a5a5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            overflow-x: auto;
            display: block;
            max-height: 750px;
            overflow-y: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e2e2e2;
            position: sticky;
            top: 0;
            z-index: 2;
            min-width: 200px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #000000;
            color: white;
            margin-top: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .input-field {
            padding: 8px;
            width: 100%;
            font-size: 1rem;
            margin: 5px 0;
        }

        .btn {
            padding: 10px 15px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .btn-secondary {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .btn-primary:hover, .btn-secondary:hover {
            background-color: #1976D2;
            opacity: 0.8;
        }

        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            th, td {
                padding: 8px;
                font-size: 14px;
            }

            .back-button {
                font-size: 1rem;
                padding: 8px 16px;
            }

            .form-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1 class="header-text">Serhan Kombos Otomotiv</h1>
    </header>

    <div class="container">
        <!-- Search Form -->
        <form method="GET" action="display.php" class="search-form">
            <div class="form-group">
                <label for="search">Search by Customer Name: </label>
                <input type="text" name="search" id="search" class="input-field" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by customer name">
            </div>
            <div class="form-group">
                <input type="submit" value="Search" class="btn btn-primary">
            </div>
        </form>

        

        <a href="index.php" class="btn btn-secondary">Add New Car</a>

        <!-- Display Success/Error Message -->
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Plate</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Mile/KM</th>
                        <th>Accident Visual</th>
                        <th>Accident Tramer</th>
                        <th>MSF</th>
                        <th>DSF</th>
                        <th>Package</th>
                        <th>Color</th>
                        <th>Engine</th>
                        <th>Gear</th>
                        <th>Fuel</th>
                        <th>Expense Detail</th>
                        <th>Current Total Expense</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row['customer_name'] . "</td>
                            <td>" . $row['plate'] . "</td>
                            <td>" . $row['brand'] . "</td>
                            <td>" . $row['model'] . "</td>
                            <td>" . $row['km_mile'] . "</td>
                            <td>" . $row['accident_visual'] . "</td>
                            <td>" . $row['accident_tramer'] . "</td>
                            <td>" . $row['msf'] . "</td>
                            <td>" . $row['dsf'] . "</td>
                            <td>" . $row['package'] . "</td>
                            <td>" . $row['color'] . "</td>
                            <td>" . $row['engine'] . "</td>
                            <td>" . $row['gear'] . "</td>
                            <td>" . $row['fuel'] . "</td>
                            <td>" . $row['expense_detail'] . "</td>
                            <td>" . $row['current_total_expense'] . "</td>
                            <td><a href='index.php?edit_id=" . $row['id'] . "' class='btn btn-edit'>Edit</a></td>
                            <td><a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-details">No cars found</p>
        <?php endif; ?>

        <!-- Back Button -->
        <a href="home.php" class="back-button">Back to Home</a>
    </div>

    <footer>
        <p>&copy; 2025 Serhan Kombos Otomotiv. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
