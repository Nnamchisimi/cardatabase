<?php
include('db_connection.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, display the message and a button to go back to login
    echo '
    <div style="text-align: center; margin-top: 50px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 20px; border-radius: 8px;">
        <h2>You need to be logged in to view car details.</h2>
        <p>Please log in first.</p>
        <a href="login.php" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Login</a>
    </div>';
    exit;  // Stop the script from executing further
}



// Initialize variables
$search_query = '';

// Check if the search query is set in the URL
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);  // sanitize input
    // Remove spaces and hyphens from the search query
    $search_query_cleaned = str_replace([' ', '-'], '', $search_query);
    
    // Search across plate, chasis, and customer_name columns
    $sql = "SELECT * FROM cars WHERE 
            (REPLACE(REPLACE(LOWER(plate), ' ', ''), '-', '') LIKE '%$search_query_cleaned%')  OR
            chasis LIKE '%$search_query%' OR
            customer_name LIKE '%$search_query%'";
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
        background-color: #212121; /* Darker header background for professionalism */
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }

    .header-text {
        font-size: 2.2rem;
        font-weight: bold;
        color: #e0d8d8;
    }

    .no-details {
        color: #212121;
        font-weight: bold;
        text-align: center;
        margin-top: 30px;
        font-size: 1.1rem;
    }

    .back-button {
        display: block;
        margin: 20px auto;
        padding: 12px 24px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 6px;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.3s ease;
        max-width: 250px;
        width: auto;
    }

    .back-button:hover {
        background-color: #555;
        transform: translateY(-2px);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        overflow-x: auto;
        display: block;
        max-height: 530px;
        overflow-y: auto;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
        font-size: 1rem;
    }

    th {
        background-color: #f4f4f4;
        position: sticky;
        top: 0;
        z-index: 2;
        min-width: 200px;
        font-weight: 600;
        color: #333;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:nth-child(odd) {
        background-color: #fff;
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: #212121;
        color: white;
        margin-top: auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .input-field {
        padding: 10px;
        width: 20%;
        font-size: 1rem;
        margin: 8px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        transition: border-color 0.3s ease;
    }

    .input-field:focus {
        border-color: #2196F3;
        outline: none;
    }

    .btn {
        padding: 12px 20px;
        font-size: 1rem;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #2196F3;
        color: white;
        border: none;
    }

    .btn-secondary {
        background-color: #4CAF50;
        color: white;
        border: none;
    }

    .btn-primary:hover, .btn-secondary:hover {
        background-color: #1976D2;
        opacity: 0.8;
    }

    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
        text-align: center;
        z-index: 9999;
        border-radius: 10px;
    }

    .popup img {
        width: 50%;
        border-radius: 10px;
        cursor: pointer;
    }

    .popup button {
        margin-top: 20px;
        padding: 12px 24px;
        background: #333;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .popup button:hover {
        background-color: #555;
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

    /* Styling the nav container */
    nav {
        display: flex;
        justify-content: center;
        background-color: #333; /* Darker background for a more professional look */
        padding: 20px 10;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 2px;
        height: 50%;
        transition: all 0.3s ease;
    }

    /* Styling the individual links in the nav */
    nav a {
        color: #f1f1f1;
        padding: 10px 10px;
        font-size: 1.2rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        border-radius: 5px;
        margin: 0 20px;
        position: relative;
        transition: all 0.3s ease;
    }

    /* Hover effect for navigation links */
    nav a:hover {
        background-color: #555;
        color: #fff;
        transform: translateY(-6px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    /* Underline on hover */
    nav a:hover::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #fff;
        border-radius: 5px;
    }

    /* Active link style */
    nav a.active {
        background-color: #ff5722;
        color: #fff;
        font-weight: 700;
    }

    /* Responsive Design for Mobile */
    @media (max-width: 768px) {
        nav {
            flex-direction: column;
            padding: 10px;
            margin-top: 0;
        }

        nav a {
            padding: 16px;
            margin: 8px 0;
            width: 100%;
            text-align: center;
        }

        /* Add hamburger icon for mobile screens */
        .hamburger {
            display: block;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
            background-color: transparent;
            border: none;
        }

        nav.responsive {
            display: block;
            text-align: center;
        }

        nav.responsive a {
            width: 100%;
        }
    }

    /* Responsive styles for mobile */
    @media (max-width: 768px) {
        th, td {
            padding: 10px;
            font-size: 14px;
        }

        .back-button {
            font-size: 1rem;
            padding: 10px 18px;
        }

        .form-group {
            width: 100%;
        }
        /* Styling for the icons */
        .edit-icon, .delete-icon {
            font-size: 22px; /* Larger icon size */
            margin-right: 8px; /* Space between icon and text */
        }

        .arrow {
            position: absolute;
            top: 50%;
            background: #333;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10000;
        }

        .left-arrow {
            left: 20px;
        }

        .right-arrow {
            right: 20px;
        }

        .arrow:hover {
            background: #555;
        }
        .table-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    overflow-x: auto; /* In case the table overflows */
}

    }
</style>

</head>
<body>
    <header>
        <h1 class="header-text">Serhan Kombos Otomotiv</h1>
        <nav>
        <a href="home.php">Home</a>
        <a href="index.php">Add Car</a>
    </nav>
    </header>

    <div class="container">
        <!-- Search Form -->
        <form method="GET" action="userdisplay.php" class="search-form">
            <div class="form-group">
                <label for="search">Search by Customer Name: </label>
                <input type="text" name="search" id="search" class="input-field" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by customer name">
                <input type="submit" value="Search" class="btn btn-primary">
            </div>
        </form>

        

        <!-- Display Success/Error Message -->
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
         <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        

                        <th>Customer Name</th>
                        <th>Plate</th>
                        <th>Chasis</th>
                        <th>Brand</th>
                        <th>Year</th>
                        <th>Model</th>
                        <th>Mile/KM</th>
                        <th>Accident Visual</th>
                        <th>Accident Tramer</th>
                        <th>MSF</th>
                        <th>DSF</th>
                        <th>GSF</th>
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
                        $image_path2 = $row['image2'] ? 'uploads/' . $row['image2'] : 'uploads/default.jpg'; // Default image2 if none is uploaded
                        $image_path3 = $row['image3'] ? 'uploads/' . $row['image3'] : 'uploads/default.jpg'; // Default image if none is uploaded
                        $image_path4 = $row['image4'] ? 'uploads/' . $row['image4'] : 'uploads/default.jpg'; // Default image2 if none is uploaded

                        echo "<tr>
                            <td>
                                      <img src='" . $image_path . "' style='width: 100px;' onclick='showDetails(\"" . $row['customer_name'] . "\", \"" . $row['plate'] . "\",  \"" . $row['chasis'] . "\",\"" . $row['brand'] . "\",\"" . $row['year'] . "\", \"" . $row['model'] . "\", \"" . $row['km_mile'] . "\", \"" . $row['accident_visual'] . "\", \"" . $row['accident_tramer'] . "\", \"" . $row['msf'] . "\", \"" . $row['dsf'] . "\", \"".$row['gsf'] . "\" , \"" . $row['package'] . "\", \"" . $row['color'] . "\", \"" . $row['engine'] . "\", \"" . $row['gear'] . "\", \"" . $row['fuel'] . "\", \"" . $row['expense_detail'] . "\", \"" . $row['current_total_expense'] . "\", \"" . $image_path . "\", \"" . $image_path2 . "\", \"" . $image_path3 . "\", \"" . $image_path4 . "\")'>
                            </td>

                            <td>" . $row['customer_name'] . "</td>
                            <td>" . $row['plate'] . "</td>
                            <td>" . $row['chasis'] . "</td>
                            <td>" . $row['brand'] . "</td>
                            <td>" . $row['year'] . "</td>
                            <td>" . $row['model'] . "</td>
                            <td>" . $row['km_mile'] . "</td>
                            <td>" . $row['accident_visual'] . "</td>
                            <td>" . $row['accident_tramer'] . "</td>
                            <td>" . $row['msf'] . "</td>
                            <td>" . $row['dsf'] . "</td>
                            <td>" . $row['gsf'] . "</td>
                            <td>" . $row['package'] . "</td>
                            <td>" . $row['color'] . "</td>
                            <td>" . $row['engine'] . "</td>
                            <td>" . $row['gear'] . "</td>
                            <td>" . $row['fuel'] . "</td>
                            <td>" . $row['expense_detail'] . "</td>
                            <td>" . $row['current_total_expense'] . "</td>
                            
                            
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
         </div>
        <?php else: ?>
            <p class="no-details">No cars found</p>
        <?php endif; ?>
        
        <!-- Back Button -->
        <a href="home.php" class="back-button">Back to Home</a>
    </div>

    <div class="overlay" id="overlay" onclick="closePopup()"></div>

            <div class="popup" id="popup">
            <button class="arrow left-arrow" onclick="previousImage()">⬅️</button>
            <img id="popup-img" src="" alt="Car Image" onclick="toggleImageSize()">
            <button class="arrow right-arrow" onclick="nextImage()">➡️</button>
            <h2 id="popup-name"></h2>
            <p><strong>Plate:</strong> <span id="popup-plate"></span></p>
            <p><strong>Chasis:</strong> <span id="popup-chasis"></span></p>
            <p><strong>Brand:</strong> <span id="popup-brand"></span></p>
            <p><strong>Year:</strong> <span id="popup-year"></span></p>
            <p><strong>Model:</strong> <span id="popup-model"></span></p>
            <p><strong>Mile/KM:</strong> <span id="popup-km_mile"></span></p>
            <p><strong>Accident Visual:</strong> <span id="popup-accident_visual"></span></p>
            <p><strong>Accident Tramer:</strong> <span id="popup-accident_tramer"></span></p>
            <p><strong>MSF:</strong> <span id="popup-msf"></span></p>
            <p><strong>DSF:</strong> <span id="popup-dsf"></span></p>
            <p><strong>GSF:</strong> <span id="popup-gsf"></span></p>
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
                   let imageIndex = 0;
                    let images = [];

                    function showDetails(name, plate,chasis, brand,year, model, km_mile, accident_visual, accident_tramer, msf, dsf,gsf, package, color, engine, gear, fuel, expense_detail, current_total_expense, image, image2,image3,image4) {
                        images = [image, image2,image3,image4];

                        imageIndex = 0;

                        document.getElementById('popup-name').textContent = name;
                        document.getElementById('popup-plate').textContent = plate;
                        document.getElementById('popup-chasis').textContent = chasis;
                        document.getElementById('popup-brand').textContent = brand;
                        document.getElementById('popup-year').textContent = year;
                        document.getElementById('popup-model').textContent = model;
                        document.getElementById('popup-km_mile').textContent = km_mile;
                        document.getElementById('popup-accident_visual').textContent = accident_visual;
                        document.getElementById('popup-accident_tramer').textContent = accident_tramer;
                        document.getElementById('popup-msf').textContent = msf;
                        document.getElementById('popup-dsf').textContent = dsf;
                        document.getElementById('popup-gsf').textContent = gsf;
                        document.getElementById('popup-package').textContent = package;
                        document.getElementById('popup-color').textContent = color;
                        document.getElementById('popup-engine').textContent = engine;
                        document.getElementById('popup-gear').textContent = gear;
                        document.getElementById('popup-fuel').textContent = fuel;
                        document.getElementById('popup-expense_detail').textContent = expense_detail;
                        document.getElementById('popup-current_total_expense').textContent = current_total_expense;
                        document.getElementById('popup-img').src = images[imageIndex];

                        document.getElementById('overlay').style.display = 'block';
                        document.getElementById('popup').style.display = 'block';
                    }

                    function nextImage() {
                        imageIndex = (imageIndex + 1) % images.length;
                        document.getElementById('popup-img').src = images[imageIndex];
                    }

                    function previousImage() {
                        imageIndex = (imageIndex - 1 + images.length) % images.length;
                        document.getElementById('popup-img').src = images[imageIndex];
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
