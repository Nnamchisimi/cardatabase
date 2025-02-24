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
    }
} else {
    // Default values for a new car entry
    $customer_name = $plate = $brand = $model = $km_mile = $accident_visual = $accident_tramer = $msf = $dsf = $package = $color = $engine = $gear = $fuel = $expense_detail = $current_expense = '';
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

    if (isset($_GET['edit_id'])) {
        // Update the car details
        $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', brand = '$brand', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense' WHERE id = '$edit_id'";
        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate, $brand, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense);
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert new car data
        $sql = "INSERT INTO cars (customer_name, plate, brand, model, km_mile, accident_visual, accident_tramer, msf, dsf, package, color, engine, gear, fuel, expense_detail, current_total_expense) 
        VALUES ('$customer_name', '$plate', '$brand', '$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense')";
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
    <script>
        // Car models based on brands
        const carModels = {
            "Chrysler": ["300 C", "300 M", "Concorde", "Crossfire", "LHS", "Neon", "PT Cruiser", "Sebring", "Stratus"],
            "Audi": ["A3", "A4", "A6", "Q5", "Q7", "TT"],
            "BMW": ["X1", "X3", "X5", "3 Series", "5 Series", "7 Series"],
            "Mercedes-Benz": ["C-Class", "E-Class", "S-Class", "GLA", "GLC", "GLE"],
            "Toyota": ["Corolla", "Camry", "RAV4", "Hilux", "Land Cruiser"],
            "Volkswagen": ["Golf", "Passat", "Polo", "Tiguan", "Touareg"],
            "Nissan": ["Series", "350 Z", "370 Z", "Name", "Almera", "Altima", "Bluebird", "Cedric", "Cube", "Datsun", "Dayz", "Figaro", "GT-R", "Latio", "Laurel Altima", "Leaf", "March", "Maxima", "Micra", "Note", "NV200", "NV350", "NX Coupe", "Pino", 
                        "Primera", "Pulsar", "Serena", "Silvia", "Skyline", "Sunny", "Sylphy", "Teana", "Tiida", "Wingroad"]
        };

        // Function to update models based on brand selection
        function updateModels() {
            const brandDropdown = document.getElementById("brand");
            const modelDropdown = document.getElementById("model");

            // Clear current options
            modelDropdown.innerHTML = '<option value="">Select Model</option>';

            // Get selected brand
            const selectedBrand = brandDropdown.value;

            // Populate models if brand selected
            if (selectedBrand && carModels[selectedBrand]) {
                carModels[selectedBrand].forEach(model => {
                    const option = document.createElement("option");
                    option.value = model;
                    option.textContent = model;
                    modelDropdown.appendChild(option);
                });
            }
        }
    </script>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <div style="max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h1 style="text-align: center; color: #333;">Car Database</h1>
        <h2 style="text-align: center; color: #555;"><?php echo isset($_GET['edit_id']) ? 'Edit Car' : 'Add New Car'; ?></h2>

        <form action="index.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>" method="post" style="display: grid; gap: 15px;">
            <?php 
            $fields = [
                'customer_name' => 'Customer Name', 'plate' => 'Plate', 'km_mile' => 'Mile/KM',
                'accident_visual' => 'Accident Visual', 'accident_tramer' => 'Accident Tramer', 
                'msf' => 'MSF', 'dsf' => 'DSF', 'package' => 'Package', 'color' => 'Color', 
                'engine' => 'Engine',
                'expense_detail' => 'Expense Detail', 'current_expense' => 'Current Total Expense'
            ];

            foreach ($fields as $id => $label) {
                echo "
                    <div>
                        <label for='$id'>$label:</label><br>
                        <input type='text' id='$id' name='$id' value='" . ($$id ?? '') . "' required style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;' />
                    </div>
                ";
            }
            ?>

             
            <!-- Dropdown for GEAR -->
            <div>
                <label for="gear">GEAR:</label><br>
                <select id="gear" name="gear" onchange="updateModels()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="">Select Gear Type</option>
                    <!-- Include brands in the dropdown -->
                    <?php
                    $gear = ["MANUAL/DUZ","AUTOMATIC/OTOMATİK"];
                    foreach ($gear as $gearOption) {
                        $selected = ($gear == $gearOption) ? 'selected' : '';
                        echo "<option value='$gearOption' $selected>$gearOption</option>";
                    }
                    ?>
                </select>
            </div>

            
            <!-- Dropdown for FUEL -->
            <div>
                <label for="fuel">FUEL:</label><br>
                <select id="fuel" name="fuel" onchange="updateModels()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value=""></option>
                    <!-- Include brands in the dropdown -->
                    <?php
                    $fuel = ["PETROL/BENZENE","DIESEL/DIZEL"];
                    foreach ($fuel as $fuelOption) {
                        $selected = ($fuel == $fuelOption) ? 'selected' : '';
                        echo "<option value='$fuelOption' $selected>$fuelOption</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Dropdown for Brand -->
            <div>
                <label for="brand">Brand:</label><br>
                <select id="brand" name="brand" onchange="updateModels()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="">Select Brand</option>
                    <!-- Include brands in the dropdown -->
                    <?php
                    $brands = ["Acura", "Alfa Romeo", "Audi", "BMW", "Chevrolet", "Chrysler", "Citroen", "Dodge", "Fiat", "Ford", "Honda", "Hyundai", "Infiniti", "Jaguar", "Kia", "Lexus", "Mazda", "Mercedes-Benz", "Nissan", "Peugeot", "Renault", "Subaru", "Toyota", "Volkswagen"];
                    foreach ($brands as $brandOption) {
                        $selected = ($brand == $brandOption) ? 'selected' : '';
                        echo "<option value='$brandOption' $selected>$brandOption</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Dropdown for Model -->
            <div>
                <label for="model">Model:</label><br>
                <select id="model" name="model" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="">Select Model</option>
                    <?php 
                    // Only show models for the selected brand
                    if ($brand) {
                        // Define the models for each brand
                        $carModels = [
                            "Chrysler" => ["300 C", "300 M", "Concorde", "Crossfire", "LHS", "Neon", "PT Cruiser", "Sebring", "Stratus"],
                            "Audi" => ["A3", "A4", "A6", "Q5", "Q7", "TT"],
                            "BMW" => ["X1", "X3", "X5", "3 Series", "5 Series", "7 Series"],
                            "Mercedes-Benz" => ["C-Class", "E-Class", "S-Class", "GLA", "GLC", "GLE"],
                            "Toyota" => ["Corolla", "Camry", "RAV4", "Hilux", "Land Cruiser"],
                            "Volkswagen" => ["Golf", "Passat", "Polo", "Tiguan", "Touareg"],
                            "Nissan" => ["Series", "350 Z", "370 Z", "Name", "Almera", "Altima", "Bluebird", "Cedric", "Cube", "Datsun", "Dayz", "Figaro", "GT-R", "Latio", "Laurel Altima", "Leaf", "March", "Maxima", "Micra", "Note", "NV200", "NV350", "NX Coupe", "Pino", 
                                        "Primera", "Pulsar", "Serena", "Silvia", "Skyline", "Sunny", "Sylphy", "Teana", "Tiida", "Wingroad"]
                        ];
                        
                        foreach ($carModels[$brand] as $modelOption) {
                            $selected = ($model == $modelOption) ? 'selected' : '';
                            echo "<option value='$modelOption' $selected>$modelOption</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div style="text-align: center;">
                <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    <?php echo isset($_GET['edit_id']) ? 'Update Car' : 'Add Car'; ?>
                </button>
            </div>
        </form>

        <br>
        <div style="text-align: center;">
            <a href="display.php" style="text-decoration: none; padding: 10px 20px; background: #f44336; color: white; border-radius: 5px;">View Submitted Cars</a>
        </div>
    </div>
</body>
</html>
