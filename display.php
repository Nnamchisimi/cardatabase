<?php
include('db_connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$search_query = '';
$search_terms = [];
$search_sql = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query_cleaned = strtolower(str_replace([' ', '-', "\t", "\n", "\r"], '', $search_query));
    $search_terms = preg_split('/[\s\-]+/', strtolower(trim($search_query)));

    $normalized_columns = [
        "LOWER(REPLACE(REPLACE(brand, ' ', ''), '-', ''))",
        "LOWER(REPLACE(REPLACE(model, ' ', ''), '-', ''))",
        "LOWER(REPLACE(REPLACE(year, ' ', ''), '-', ''))",
        "LOWER(REPLACE(REPLACE(customer_name, ' ', ''), '-', ''))",
        "LOWER(REPLACE(REPLACE(chasis, ' ', ''), '-', ''))",
        "LOWER(REPLACE(REPLACE(plate, ' ', ''), '-', ''))"
    ];

    $normalized_whole_match = [];
    foreach ($normalized_columns as $col) {
        $normalized_whole_match[] = "$col LIKE '%$search_query_cleaned%'";
    }

    $normalized_term_clauses = [];
    foreach ($search_terms as $term) {
        $term_clauses = [];
        foreach ($normalized_columns as $col) {
            $term_clauses[] = "$col LIKE '%$term%'";
        }
        $normalized_term_clauses[] = '(' . implode(' OR ', $term_clauses) . ')';
    }

    $search_sql = " WHERE (" . implode(' OR ', $normalized_whole_match) . ") OR (" . implode(' AND ', $normalized_term_clauses) . ")";
}

//  Pagination Logic
$results_per_page = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $results_per_page;

//  Count total rows for pagination
$count_sql = "SELECT COUNT(*) AS total FROM cars" . $search_sql;
$count_result = mysqli_query($conn, $count_sql);
$row_count = mysqli_fetch_assoc($count_result);
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $results_per_page);

// Fetch data for the current page
$sql = "SELECT * FROM cars" . $search_sql . " ORDER BY created_at DESC LIMIT $results_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);


if (!$result) {
    die("Error in query execution: " . mysqli_error($conn));
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

    .header-with-actions {
    background-color: #000000;
    padding: 20px;
    border-bottom: 2px solid #ccc;
    font-family: Arial, sans-serif;
}

.header-with-actions h1 {
    height: 10px;
    margin: 0 0 10px 0;
    font-size: 28px;
    text-align: center;
    color: white;
}

.header-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
}

.page-title {
    grid-column: 2;
    margin: 0;
    font-size: 20px;
    text-align: center;
    color: #444;
}

.action-links {
    grid-column: 3;
    justify-self: end;
    display: flex;
    gap: 15px;
}

.action-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    height: 30px;
    width: 103px;
}

.action-link img {
    height: 20px;
    width: 20px;
    margin-right: 6px;
}
        .action-links a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.5s ease;
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
    display: block;
    max-height: 600px;
    overflow-y: auto;
}

/* Sticky table headers */
th {
    background-color: #f4f4f4;
    position: sticky;
    top: 0;
    z-index: 2;
    min-width: 105px;
    font-weight: 600;
    color: #333;
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    font-size: 0.9rem;
}


td {
    border: 1px solid #ddd;
    padding: 2px 2px;
    text-align: center;
    font-size: 0.9rem;
    vertical-align: middle;
    height: 60px;
    width: 10px;
    max-height: 60px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}


tr:nth-child(even) {
    background-color: #f9f9f9;
}
tr:nth-child(odd) {
    background-color: #fff;
}


.cell-content {
    max-height: 60px;
    overflow-y: auto;
    overflow-x: hidden;
    white-space: normal;
    padding-right: 8px;
}


table img {
    width: 120px;
    height: 60px;
    object-fit: fill;
    border-radius: 10px;
}

.pagination {
       margin-top: 15px;
    margin-bottom: 30px; 
    text-align: center;
}

.pagination a {
    color: #000000;
    padding: 8px 12px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 2px;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #f0f0f0;
}

.pagination a.active {
    background-color: #000000;
    color: white;
    border-color: #0000000;
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
        text-align: Left;
        z-index: 9999;
        border-radius: 10px;
    }

    .popup img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    max-height: 200px;
    border-radius: 10px;
    cursor: pointer;
}

    .popup p {
    font-size: 11px; 
    line-height: 1; 
    margin-bottom: 2px; 
}
.popup input[type="checkbox"] {
    width: 8px;  
    height: 8px; 
    transform: scale(1.5); 
    margin-right: 1px; 
}

.popup-image-wrapper {
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin-bottom: 10px;
}



    .popup button {
        margin-top: 4px;
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

    nav {
        display: flex;
        justify-content: center;
        background-color: #333; 
        padding: 20px 10;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 2px;
        height: 50%;
        transition: all 0.3s ease;
    }


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


    nav a:hover {
        background-color: #555;
        color: #fff;
        transform: translateY(-6px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

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
    

    nav a.active {
        background-color: #ff5722;
        color: #fff;
        font-weight: 700;
    }


#filters-sort-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-button {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

.dropdown-button:hover {
    background-color: #0056b3;
}


.dropdown-content {
    position: absolute;
    background-color: white;
    min-width: 200px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ccc;
    padding: 10px;
    z-index: 9999;
}
#filter-dropdowns {
    position: absolute;
  
    background: white;
    z-index: 9999;
    border: 1px solid #ccc;
    padding: 10px;
    max-height: 400px;
    overflow-y: auto;
    width: 300px; 
    display: none; 
}


.dropdown-content label {
    display: block;
    margin: 5px 0;
}

.dropdown-content input {
    margin-right: 5px;
}

.dropdown:hover .dropdown-content {
    display: block;
}


#sort-select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}


    


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

        th, td {
            padding: 5px;
            font-size: 7px;
        }

        .back-button {
            font-size: 1rem;
            padding: 2px 5px;
        }

        .form-group {
            width: 100%;
        }

        .edit-icon, .delete-icon {
            font-size: 22px; 
            margin-right: 8px; 
        }

        .arrow {
            position: absolute;
            top: 10%;
            background: #333;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
            border-radius: 20%;
            z-index: 10000;
        }

        .left-arrow {
            left: 5px;
        }

        .right-arrow {
            right: 5px;
        }

        .arrow:hover {
            background: #555;
        }
        .table-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    overflow-x: auto;
}

    }
</style>

</head>
<body>
<header class="header-with-actions">
    <h1>SERHAN KOMBOS OTOMOTIV</h1>

    <div class="header-row">
        <div class="action-links">

        <nav>
            <a href="index.php" class="action-link">
                <img src="add.png" alt="Add Icon">
                Add cars
            </a>
            </nav>
            <nav>
            <a href="home.php" class="action-link">
                <img src="homw.png" alt="Home Icon">
                Home
            </a>
            </nav>
          
        </div>
    </div>
</header>

    <div class="container">
       
        <form method="GET" action="display.php" class="search-form">
            <div class="form-group">
                <label for="search">Search: </label>
                <input type="text" name="search" id="search" class="input-field" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Enter a plate, chasis, or customer name">
                <input type="submit" value="Search" class="btn btn-primary">
            </div>
        </form>

<div id="filters-sort-container" style="position: relative; margin-bottom: 20px;">
 
    <div class="dropdown">
        <button class="dropdown-button" id="filter-toggle" onclick="toggleFilters()">+ Show Filters</button>
        <div id="filter-dropdowns" style="display: none; margin-top: 10px; border: 1px solid #ccc; padding: 10px; background: #fff; max-height: 400px; overflow-y: auto;">
      
            <div id="filter-container"></div>
            <button type="button" id="clear-filters" onclick="clearFilters()" style="margin-top: 10px; padding: 5px 10px; background-color: red; color: white; border: none;">Clear Filters</button>
        </div>
    </div>


<div class="dropdown">
    <label for="sort-select">Sort by:</label>
    <select id="sort-select" onchange="handleSortChange()">
        <option value="">-- Select Sorting Option --</option>

        <option value="Created at-asc">Created At (Oldest to Newest)</option>
        <option value="Created at-desc">Created At (Newest to Oldest)</option>

        <option value="Updated at-asc">Updated At (Oldest to Newest)</option>
        <option value="Updated at-desc">Updated At (Newest to Oldest)</option>

        <option value="Customer Name-asc">Customer Name (A → Z)</option>
        <option value="Customer Name-desc">Customer Name (Z → A)</option>
    </select>
</div>

</div>

        

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
                        
                        <th>Plate</th>
                        <th>Year</th>
                         <th>Brand</th>
                        <th>Model</th>
                         <th>Mile/KM</th>
                        <th>Accident Visual</th>
                        <th>Accident Tramer</th>
                         <th>Customer Name</th>
                          <th>Chasis</th>
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
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Created by</th>
                        <th>Updated by</th>
                       
                       
                       
                       
                       
                       
                      
                    </tr>
                </thead>
                <tbody>
                <?php
while($row = $result->fetch_assoc()) {
   
    $image_path = $row['image'] ? 'uploads/' . $row['image'] : 'uploads/default.jpg'; // Default image if none is uploaded
    $image_path2 = $row['image2'] ? 'uploads/' . $row['image2'] : 'uploads/default.jpg';
    $image_path3 = $row['image3'] ? 'uploads/' . $row['image3'] : 'uploads/default.jpg'; 
    $image_path4 = $row['image4'] ? 'uploads/' . $row['image4'] : 'uploads/default.jpg'; 

    echo "<tr>
        <td><img src='" . $image_path . "' onclick='showDetails(\"" . $row['customer_name'] . "\", \"" . $row['plate'] . "\", \"" . $row['chasis'] . "\", \"" . $row['brand'] . "\", \"" . $row['year'] . "\", \"" . $row['model'] . "\", \"" . $row['km_mile'] . "\", \"" . $row['accident_visual'] . "\", \"" . $row['accident_tramer'] . "\", \"" . $row['msf'] . "\", \"" . $row['dsf'] . "\", \"" . $row['gsf'] . "\", \"" . $row['package'] . "\", \"" . $row['color'] . "\", \"" . $row['engine'] . "\", \"" . $row['gear'] . "\", \"" . $row['fuel'] . "\", \"" . $row['expense_detail'] . "\", \"" . $row['current_total_expense'] . "\", \"" . $image_path . "\", \"" . $image_path2 . "\", \"" . $image_path3 . "\", \"" . $image_path4 . "\", \"" . $row['created_at'] . "\", \"" . $row['updated_at'] . "\", \"" . $row['created_by'] . "\", \"" . $row['updated_by'] . "\")'></td>
         
        <td>" . $row['plate'] . "</td>
        <td>" . $row['year'] . "</td>
        <td>" . $row['brand'] . "</td>
        <td>" . $row['model'] . "</td>
        <td>" . $row['km_mile'] . "</td>
        <td>" . $row['accident_visual'] . "</td>
        <td>" . $row['accident_tramer'] . "</td>
        <td>" . $row['customer_name'] . "</td>
        <td>" . substr($row['chasis'], 0, 7) . "</td>
        <td>" . $row['msf'] . "</td>
        <td>" . $row['dsf'] . "</td>
        <td>" . $row['gsf'] . "</td>
        <td>" . $row['package'] . "</td>
        <td>" . $row['color'] . "</td>
        <td>" . $row['engine'] . "</td>
        <td>" . $row['gear'] . "</td>
        <td>" . $row['fuel'] . "</td>
        <td><div class='cell-content'>" . $row['expense_detail'] . "</div></td>
        <td>" . $row['current_total_expense'] . "</td>
        <td data-column='created_at'>" . $row['created_at'] . "</td>
        <td data-column='updated_at'>" . $row['updated_at'] . "</td>
        <td>" . $row['created_by'] . "</td>
        <td>" . $row['updated_by'] . "</td>
      
      
        
        <td><a href='index.php?edit_id=" . $row['id'] . "' class='btn btn-edit'>Edit</a></td>
        <td><a href='delete.php?id=" . $row['id'] . "' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a></td>
    </tr>";
}
?>

                </tbody>
            </table>
         </div>
        <?php else: ?>
            <p class="no-details">No cars found</p>
        <?php endif; ?>
    </div>

    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    <div class="popup" id="popup">
    <div class="popup-image-wrapper" style="position: relative;">
        <!-- Print Button -->
        <button onclick="printPopup()" title="Print" style="position: absolute; top: 0px; right: 10px; background: none; border: none; cursor: pointer;">
    <img src="uploads/printing.png" alt="Print" style="width: 20px; height: 20px;">
    </button>

        <!-- Image Navigation -->
        <button class="arrow left-arrow" onclick="previousImage()">⬅️</button>
        <img id="popup-img" src="" alt="Car Image" onclick="toggleImageSize()">
        <button class="arrow right-arrow" onclick="nextImage()">➡️</button>
    </div>



<p>
  <label>
    <input type="checkbox" id="select-all-checkbox">
    <strong>Select All</strong>
  </label>
</p>
    <p>
  <label>
    <input type="checkbox" class="print-checkbox" unchecked>
    <strong>Name:</strong>
  </label>
  <span id="popup-name"></span>
</p>
    <!-- Each Detail with Checkboxes -->
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Plate:</strong></label> <span id="popup-plate"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Chasis:</strong></label> <span id="popup-chasis"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Brand:</strong></label> <span id="popup-brand"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Year:</strong></label> <span id="popup-year"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Model:</strong></label> <span id="popup-model"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Mile/KM:</strong></label> <span id="popup-km_mile"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Accident Visual:</strong></label> <span id="popup-accident_visual"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Accident Tramer:</strong></label> <span id="popup-accident_tramer"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>MSF:</strong></label> <span id="popup-msf"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>DSF:</strong></label> <span id="popup-dsf"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>GSF:</strong></label> <span id="popup-gsf"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Package:</strong></label> <span id="popup-package"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Color:</strong></label> <span id="popup-color"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Engine:</strong></label> <span id="popup-engine"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Gear:</strong></label> <span id="popup-gear"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Fuel:</strong></label> <span id="popup-fuel"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Expense Detail:</strong></label> <span id="popup-expense_detail"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Current Total Expense:</strong></label> <span id="popup-current_total_expense"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Created at:</strong></label> <span id="popup-created_at"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Updated at:</strong></label> <span id="popup-updated_at"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Created by:</strong></label> <span id="popup-created_by"></span></p>
    <p><label><input type="checkbox" class="print-checkbox" unchecked> <strong>Updated by:</strong></label> <span id="popup-updated_by"></span></p>
    <button onclick="closePopup()">Close</button>

</div>


<div class="pagination">
    <?php if ($page > 1) : ?>
        <a href="?search=<?= urlencode($search_query) ?>&page=<?= $page - 1 ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <?php if ($i == $page) : ?>
            <a class="active" href="?search=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
        <?php else : ?>
            <a href="?search=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $total_pages) : ?>
        <a href="?search=<?= urlencode($search_query) ?>&page=<?= $page + 1 ?>">Next</a>
    <?php endif; ?>
</div>


    <footer>
        <p>&copy; 2025 Serhan Kombos Otomotiv</p>
    </footer>


    
    
    <script>
                   let imageIndex = 0;
                    let images = [];

                    function showDetails(name, plate,chasis, brand,year, model, km_mile, accident_visual, accident_tramer, msf, dsf,gsf, package, color, engine, gear, fuel, expense_detail, current_total_expense, image, image2,image3,image4,created_at,updated_at,created_by,updated_by) {
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
                        document.getElementById('popup-created_at').textContent = created_at;
                        document.getElementById('popup-img').src = images[imageIndex];
                            document.getElementById('popup-created_at').textContent = created_at;
                            document.getElementById('popup-updated_at').textContent = updated_at;
                            document.getElementById('popup-created_by').textContent = created_by;
                            document.getElementById('popup-updated_by').textContent = updated_by;

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
                    let originalRows = [];
let currentSort = { column: null, order: null };
let activeFilters = {};

window.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('table tbody');
    originalRows = Array.from(table.rows);

    // Sort by Created at (newest first) on page load
    sortTableByCreatedAtDescending();
    
    // Update originalRows to reflect the sorted state
    originalRows = Array.from(document.querySelector('table tbody').rows);
    

    const headers = [
        "Customer Name", "Plate", "Chasis", "Brand", "Year", "Model", "Mile/KM",
        "Accident Visual", "Accident Tramer", "MSF", "DSF", "GSF", "Package",
        "Color", "Engine", "Gear", "Fuel", "Created at", "Updated at", "Created by", "Updated by"
    ];

    const columnIndexMap = {
        "Created at": 1, "Updated at": 2, "Created by": 3, "Updated by": 4, "Customer Name": 5,
        "Plate": 6, "Chasis": 7, "Brand": 8, "Year": 9, "Model": 10, "Mile/KM": 11,
        "Accident Visual": 12, "Accident Tramer": 13, "MSF": 14, "DSF": 15, "GSF": 16,
        "Package": 17, "Color": 18, "Engine": 19, "Gear": 20, "Fuel": 21, "Expense Detail": 22,
        "Current Total Expense": 23
    };

    const filterContainer = document.getElementById('filter-container');

    headers.forEach(header => {
        const colIndex = columnIndexMap[header];
        const uniqueValues = new Set(originalRows.map(row => row.cells[colIndex]?.innerText.trim()));

        const wrapper = document.createElement('div');
        wrapper.style.marginBottom = '10px';

        const toggle = document.createElement('button');
        toggle.textContent = '+ ' + header;
        toggle.type = 'button';
        toggle.style.display = 'block';
        toggle.style.marginBottom = '5px';

        const checkboxContainer = document.createElement('div');
        checkboxContainer.style.display = 'none';
        checkboxContainer.style.marginLeft = '10px';

        const clearButton = document.createElement('label');
        clearButton.style.display = 'block';
        clearButton.innerHTML = `<input type="checkbox" data-column="${colIndex}" value="clear-all" class="clear-all"> − Uncheck All`;

        checkboxContainer.appendChild(clearButton);

        uniqueValues.forEach(value => {
            const label = document.createElement('label');
            label.style.display = 'block';
            label.innerHTML = `<input type="checkbox" data-column="${colIndex}" value="${value}" checked> ${value}`;
            checkboxContainer.appendChild(label);
        });

        toggle.addEventListener('click', () => {
            checkboxContainer.style.display = checkboxContainer.style.display === 'none' ? 'block' : 'none';
            toggle.textContent = (checkboxContainer.style.display === 'none' ? '+ ' : '− ') + header;
        });

        clearButton.querySelector('input').addEventListener('change', (e) => {
            const checkboxes = checkboxContainer.querySelectorAll('input[type="checkbox"]:not(.clear-all)');
            if (e.target.checked) {
          
                checkboxes.forEach(checkbox => checkbox.checked = false);
            } else {
          
                checkboxes.forEach(checkbox => checkbox.checked = true);
            }
            applyFilters(); // Apply the filter changes after unchecking/adding
        });

        wrapper.appendChild(toggle);
        wrapper.appendChild(checkboxContainer);
        filterContainer.appendChild(wrapper);
    });

    document.querySelectorAll('#filter-container input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', applyFilters);
    });
});


function getColumnIndexByHeader(headerName) {
    const headers = document.querySelectorAll('table thead th');
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].innerText.trim().toLowerCase() === headerName.toLowerCase()) {
            return i;
        }
    }
    console.error(`Header "${headerName}" not found.`);
    return -1;
}


function sortTableByCreatedAtDescending() {
    const columnIndex = getColumnIndexByHeader('Created at');
    if (columnIndex === -1) return; 

    const table = document.querySelector('table tbody');
    const rows = Array.from(table.rows);

    rows.sort((a, b) => {
        const createdAtA = new Date(a.cells[columnIndex].innerText.trim());
        const createdAtB = new Date(b.cells[columnIndex].innerText.trim());
        return createdAtB - createdAtA; 
    });

    rows.forEach(row => table.appendChild(row));
}

// Generic sort handler
function handleSortChange() {
    const select = document.getElementById('sort-select');
    const value = select.value;

    if (!value) {
        applyFilters(); 
        return;
    }

    const [header, order] = value.split('-');
    const columnIndex = getColumnIndexByHeader(header);
    if (columnIndex === -1) return;

    const table = document.querySelector('table tbody');
    const rows = Array.from(table.rows);

    rows.sort((a, b) => {
        const textA = a.cells[columnIndex]?.innerText.trim();
        const textB = b.cells[columnIndex]?.innerText.trim();

      
        const dateA = new Date(textA);
        const dateB = new Date(textB);

        const isDate = !isNaN(dateA) && !isNaN(dateB);

        if (isDate) {
            return order === 'asc' ? dateA - dateB : dateB - dateA;
        } else {
     
            return order === 'asc' 
                ? textA.localeCompare(textB, undefined, {numeric: true})
                : textB.localeCompare(textA, undefined, {numeric: true});
        }
    });

    rows.forEach(row => table.appendChild(row));
}

function applyFilters() {
    const checkboxes = document.querySelectorAll('#filter-container input[type="checkbox"]:not(.clear-all)');
    const filters = {};

    checkboxes.forEach(cb => {
        const col = cb.dataset.column;
        if (!filters[col]) filters[col] = new Set();
        if (cb.checked) filters[col].add(cb.value);
    });

    const table = document.querySelector('table tbody');
    table.innerHTML = "";

    originalRows.forEach(row => {
        let show = true;
        for (const col in filters) {
            const cellValue = row.cells[col]?.innerText.trim();
            if (!filters[col].has(cellValue)) {
                show = false;
                break;
            }
        }
        if (show) table.appendChild(row);
    });
}


function toggleFilters() {
    const filterDropdown = document.getElementById('filter-dropdowns');
    const filterButton = document.getElementById('filter-toggle');

    if (filterDropdown.style.display === 'none' || filterDropdown.style.display === '') {
        filterDropdown.style.display = 'block';
        filterButton.textContent = '− Hide Filters';
    } else {
        filterDropdown.style.display = 'none';
        filterButton.textContent = '+ Show Filters';
    }
}

function clearFilters() {

    document.querySelectorAll('#filter-container input[type="checkbox"]:not(.clear-all)').forEach(cb => {
        cb.checked = true; 
    });

   
    const sortSelect = document.getElementById('sort-select');
    sortSelect.value = ""; 

  
    applyFilters();
}


const filterDropdown = document.getElementById('filter-dropdowns');
const filterButton = document.getElementById('filter-toggle');

document.addEventListener('click', function(event) {
    if (!filterDropdown.contains(event.target) && event.target !== filterButton) {
        filterDropdown.style.display = 'none';  
        filterButton.textContent = '+ Show Filters';  
    }
});





        function closePopup() {
           
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }

        function toggleImageSize() {
            var img = document.getElementById('popup-img');
            if (img.style.width === '50%') {
                img.style.width = '250px'; 
            } else {
                img.style.width = '50%'; 
            }
        }

        function printPopup() {
            
    const popup = document.getElementById("popup");

    const clone = popup.cloneNode(true);

    const checkboxes = clone.querySelectorAll(".print-checkbox");
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.closest("p")?.remove();
        } else {
            checkbox.style.display = "none";
        }
    });

 
    const selectAll = clone.querySelector('#select-all-checkbox');
    if (selectAll) {
        const selectAllRow = selectAll.closest('p');
        if (selectAllRow) selectAllRow.remove();
    }

    clone.querySelectorAll(".arrow, button").forEach(el => el.style.display = "none");

    const popupImg = document.getElementById("popup-img");
    const imgWidth = popupImg?.clientWidth || 0;
    const imgHeight = popupImg?.clientHeight || 0;

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Popup</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 5px;
                }
                h2 {
                    margin-top: 0;
                }
                img {
                    width: ${imgWidth}px;
                    height: ${imgHeight}px;
                    object-fit: contain;
                }
            </style>
        </head>
        <body>
            ${clone.innerHTML}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
   
}


    </script>
    
</body>
</html>
