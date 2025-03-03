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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cardatabase</title>
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
            width: 50%;
            font-size: 1rem;
            margin: 2.5px 0;
        }

        .btn {
            padding: 10px 15px;
            font-size: 1rem;
            cursor: pointer;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 600px;
            text-align: center;
            z-index: 9999;
            border-radius: 10px;
        }

        .popup img {
            width: 50%; /* Default size */
            border-radius: 10px;
            cursor: pointer;
        }

        .popup button {
            margin-top: 20px;
            padding: 10px 20px;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
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
                        <th>Image</th>
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
                         <!-- New column for image -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = $result->fetch_assoc()) {
                        // Check if an image is uploaded for the car
                        $image_path = $row['image'] ? 'uploads/' . $row['image'] : 'uploads/default.jpg'; // Default image if none is uploaded

                        echo "<tr>
                            <td>
                                <img src='" . $image_path . "' style='width: 100px;' onclick='showDetails(\"" . $row['customer_name'] . "\", \"" . $row['plate'] . "\", \"" . $row['brand'] . "\", \"" . $row['model'] . "\", \"" . $row['km_mile'] . "\", \"" . $row['accident_visual'] . "\", \"" . $row['accident_tramer'] . "\", \"" . $row['msf'] . "\", \"" . $row['dsf'] . "\", \"" . $row['package'] . "\", \"" . $row['color'] . "\", \"" . $row['engine'] . "\", \"" . $row['gear'] . "\", \"" . $row['fuel'] . "\", \"" . $row['expense_detail'] . "\", \"" . $row['current_total_expense'] . "\", \"" . $image_path . "\")'>
                            </td>

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

    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    <div class="popup" id="popup">
        <img id="popup-img" src="" alt="Car Image" onclick="toggleImageSize()">
        <h2 id="popup-name"></h2>
        <p><strong>Plate:</strong> <span id="popup-plate"></span></p>
        <p><strong>Brand:</strong> <span id="popup-brand"></span></p>
        <p><strong>Model:</strong> <span id="popup-model"></span></p>
        <p><strong>Mile/KM:</strong> <span id="popup-km_mile"></span></p>
        <p><strong>Accident Visual:</strong> <span id="popup-accident_visual"></span></p>
        <p><strong>Accident Tramer:</strong> <span id="popup-accident_tramer"></span></p>
        <p><strong>MSF:</strong> <span id="popup-msf"></span></p>
        <p><strong>DSF:</strong> <span id="popup-dsf"></span></p>
        <p><strong>Package:</strong> <span id="popup-package"></span></p>
        <p><strong>Color:</strong> <span id="popup-color"></span></p>
        <p><strong>Engine:</strong> <span id="popup-engine"></span></p>
        <p><strong>Gear:</strong> <span id="popup-gear"></span></p>
        <p><strong>Fuel:</strong> <span id="popup-fuel"></span></p>
        <p><strong>Expense Detail:</strong> <span id="popup-expense_detail"></span></p>
        <p><strong>Current Total Expense:</strong> <span id="popup-current_total_expense"></span></p>
        <button onclick="closePopup()">Close</button>
    </div>

    <footer>
        <p>&copy; 2025 Serhan Kombos Otomotiv</p>
    </footer>

    <script>
        function showDetails(name, plate, brand, model, km_mile, accident_visual, accident_tramer, msf, dsf, package, color, engine, gear, fuel, expense_detail, current_total_expense, imagePath) {
            // Populate the popup with the car details
            document.getElementById('popup-name').textContent = name;
            document.getElementById('popup-plate').textContent = plate;
            document.getElementById('popup-brand').textContent = brand;
            document.getElementById('popup-model').textContent = model;
            document.getElementById('popup-km_mile').textContent = km_mile;
            document.getElementById('popup-accident_visual').textContent = accident_visual;
            document.getElementById('popup-accident_tramer').textContent = accident_tramer;
            document.getElementById('popup-msf').textContent = msf;
            document.getElementById('popup-dsf').textContent = dsf;
            document.getElementById('popup-package').textContent = package;
            document.getElementById('popup-color').textContent = color;
            document.getElementById('popup-engine').textContent = engine;
            document.getElementById('popup-gear').textContent = gear;
            document.getElementById('popup-fuel').textContent = fuel;
            document.getElementById('popup-expense_detail').textContent = expense_detail;
            document.getElementById('popup-current_total_expense').textContent = current_total_expense;
            document.getElementById('popup-img').src = imagePath;
            
            // Show the overlay and popup
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            // Hide the overlay and popup
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }

        function toggleImageSize() {
            var img = document.getElementById('popup-img');
            if (img.style.width === '50%') {
                img.style.width = '250px'; // Larger size
            } else {
                img.style.width = '50%'; // Default size
            }
        }
    </script>
</body>
</html>
