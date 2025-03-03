<?php
include('db_connection.php');

// Check if we are editing an existing car
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
        $image = $row['image']; // Get the image filename if exists
    }
} else {
    // Default values for a new car entry
    $customer_name = $plate = $brand = $model = $km_mile = $accident_visual = $accident_tramer = $msf = $dsf = $package = $color = $engine = $gear = $fuel = $expense_detail = $current_expense = $image = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image;
        move_uploaded_file($image_tmp, $image_folder);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image = $row['image'];
    }

    if (isset($_GET['edit_id'])) {
        // Update the car details
        $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', brand = '$brand', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense', image = '$image' WHERE id = '$edit_id'";
        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate, $brand, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense,$image);
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert new car data
        $sql = "INSERT INTO cars (customer_name, plate, brand, model, km_mile, accident_visual, accident_tramer, msf, dsf, package, color, engine, gear, fuel, expense_detail, current_total_expense, image) 
        VALUES ('$customer_name', '$plate', '$brand', '$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense', '$image')";
        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate, $brand, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense);
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
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('static/images/img2.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            font-size: 3rem;
            margin: 0;
        }

        header h2 {
            font-size: 1.5rem;
            margin: 5px 0;
            color: #aaa;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 20px;
            overflow: hidden;
            box-sizing: border-box;
        }

        form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select {
            padding: 12px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
             background-color: #000;
            color: white;
            padding: 14px 28px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 200px;
        }

        button:hover {
            background-color: #555;
        }

        .action-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .action-links a {
            text-decoration: none;
            color: white;
            background-color: #45a049;
            padding: 12px 24px;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-links a:hover {
            background-color: #555;
        }

        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 15px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                width: 100%;
            }

            form {
                grid-template-columns: 1fr;
            }

            button {
                width: 100%;
            }

            .action-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Car Database</h1>
    <h2><?php echo isset($_GET['edit_id']) ? 'Edit Car' : 'SERHAN KOMBOS OTOMOTIV'; ?></h2>
</header>

<div class="container">
    <form action="index.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>" method="post" enctype="multipart/form-data">
        <?php 
        $fields = [
            'customer_name' => 'Customer Name', 'plate' => 'Plate', 'km_mile' => 'Mile/KM', 'accident_tramer' => 'Accident Tramer', 
            'msf' => 'MSF', 'dsf' => 'DSF', 'package' => 'Package', 'color' => 'Color', 
            'engine' => 'Engine', 'expense_detail' => 'Expense Detail', 'current_expense' => 'Current Total Expense'
        ];

        foreach ($fields as $id => $label) {
            echo "
                <div class='form-group'>
                    <label for='$id'>$label:</label>
                    <input type='text' id='$id' name='$id' value='" . ($$id ?? '') . "' required />
                </div>
            ";
        }
        ?>

        <div class="form-group">
            <label for="gear">Gear:</label>
            <select id="gear" name="gear"  required>
                <option value="" disabled <?php echo empty($gear) ? 'selected' : ''; ?>>Select</option>
                <?php
                $gearOptions = ["MANUAL/DUZ", "AUTOMATIC/OTOMATİK"];
                foreach ($gearOptions as $gearOption) {
                    $selected = ($gear == $gearOption) ? 'selected' : '';
                    echo "<option value='$gearOption' $selected>$gearOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="accident_visual">Accident Visual:</label>
            <select id="accident_visual" name="accident_visual" required>
                <option value="" disabled <?php echo empty($accident_visual) ? 'selected' : ''; ?>>Select</option>
                <?php
                $accident_visualOptions = ["CRITICAL DAMAGE", "MINOR DAMAGE", "NO VISUAL DAMAGE"];
                foreach ($accident_visualOptions as $accident_visualOption) {
                    $selected = ($accident_visual == $accident_visualOption) ? 'selected' : '';
                    echo "<option value='$accident_visualOption' $selected>$accident_visualOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fuel">Fuel:</label>
            <select id="fuel" name="fuel"  required>
                <option value="" disabled <?php echo empty($fuel) ? 'selected' : ''; ?>>Select</option>
                <?php
                $fuelOptions = ["PETROL/BENZENE", "DIESEL/DIZEL"];
                foreach ($fuelOptions as $fuelOption) {
                    $selected = ($fuel == $fuelOption) ? 'selected' : '';
                    echo "<option value='$fuelOption' $selected>$fuelOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="brand">Brand:</label>
            <select id="brand" name="brand" onchange="updateModels()">
                <option value="" disabled <?php echo empty($brand) ? 'selected' : ''; ?>>Select</option>
                <?php
                $brands = ["Acura", "Alfa Romeo", "Audi", "BMW", "Chevrolet", "Chrysler", "Citroen", "Dodge", "Fiat", "Ford", "Honda", "Hyundai", "Infiniti", "Jaguar", "Kia", "Lexus", "Mazda", "Mercedes-Benz", "Nissan", "Peugeot", "Renault", "Subaru", "Toyota", "Volkswagen"];
                foreach ($brands as $brandOption) {
                    $selected = ($brand == $brandOption) ? 'selected' : '';
                    echo "<option value='$brandOption' $selected>$brandOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="model">Model:</label>
            <select id="model" name="model"  >
                <option value="" disabled <?php echo empty($model) ? 'selected' : ''; ?>>Select</option>
            </select>
        </div>

        <!-- File Upload -->
      <!-- File Upload -->
<div class="form-group">
    <label for="image">Car Image:</label>
    <input type="file" id="image" name="image" <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> />
    <?php
    if (!empty($image) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image: <img src='uploads/$image' alt='Car Image' width='100' /></p>";
    }
    ?>
</div>


        
        <button type="submit"><?php echo isset($_GET['edit_id']) ? 'Update Car' : 'Add Car'; ?></button>
    </form>

    <div class="action-links">
        <a href="display.php">View Submitted Cars</a>
        <a href="home.php">Back To Home</a>
    </div>
</div>

<script>
    const carModels = {
        "Chrysler": ["300 C", "300 M", "Concorde", "Crossfire", "LHS", "Neon", "PT Cruiser", "Sebring", "Stratus"],
        "Audi": ["A3", "A4", "A6", "Q5", "Q7", "TT"],
        "BMW": ["X1", "X3", "X5", "3 Series", "5 Series", "7 Series"],
        "Mercedes-Benz": ["C-Class", "E-Class", "S-Class", "GLA", "GLC", "GLE"],
        "Toyota": ["Corolla", "Camry", "RAV4", "Hilux", "Land Cruiser"],
        "Volkswagen": ["Golf", "Passat", "Polo", "Tiguan", "Touareg"],
        "Nissan": ["350 Z", "370 Z", "Almera", "Altima", "Bluebird", "Cedric", "Cube", "Datsun"]
    };

    function updateModels() {
    const brandDropdown = document.getElementById("brand");
    const modelDropdown = document.getElementById("model");

    // Always add the "Select" option at the top
    modelDropdown.innerHTML = '<option value="" disabled selected>Select</option>';

    const selectedBrand = brandDropdown.value; // Get selected brand
    const selectedModel = "<?php echo $model; ?>"; // Get model from PHP

    if (selectedBrand && carModels[selectedBrand]) {
        // Loop through and add models based on selected brand
        carModels[selectedBrand].forEach(model => {
            const option = document.createElement("option");
            option.value = model;
            option.textContent = model;

            // Automatically select the model from the database
            if (model === selectedModel) {
                option.selected = true;
            }

            modelDropdown.appendChild(option);
        });
    }
}

    window.onload = updateModels;
    

       // Place the Confirmation Popup Here 👇👇👇
       document.querySelector("form").onsubmit = function (event) {
        const confirmation = confirm("Are you sure you want to submit the data?");
        if (!confirmation) {
            event.preventDefault(); // Stops the submission if user clicks Cancel
        }
    };
</script>

<footer>
    <p>&copy; 2025 Car Database System. All rights reserved.</p>
</footer>

</body>
</html>
