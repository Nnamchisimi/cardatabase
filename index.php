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
            header('Location: display.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert new car data
        $sql = "INSERT INTO cars (customer_name, plate, brand, model, km_mile, accident_visual, accident_tramer, msf, dsf, package, color, engine, gear, fuel, expense_detail, current_total_expense) 
        VALUES ('$customer_name', '$plate', '$brand', '$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense')";
        if ($conn->query($sql) === TRUE) {
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
                        "Primera", "Pulsar", "Serena", "Silvia", "Skyline", "Sunny", "Sylphy", "Teana", "Tiida", "Wingroad"
]

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
                'engine' => 'Engine', 'gear' => 'Gear', 'fuel' => 'Fuel', 
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

            <!-- Dropdown for Brand -->
            <!-- Dropdown for Brand -->
<div>
    <label for="brand">Brand:</label><br>
    <select id="brand" name="brand" onchange="updateModels()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
        <option value="">Select Brand</option>
        <option value="Acura" <?php if ($brand == 'Acura') echo 'selected'; ?>>Acura</option>
        <option value="Alfa Romeo" <?php if ($brand == 'Alfa Romeo') echo 'selected'; ?>>Alfa Romeo</option>
        <option value="Ahmed" <?php if ($brand == 'Ahmed') echo 'selected'; ?>>Ahmed</option>
        <option value="Aston Martin" <?php if ($brand == 'Aston Martin') echo 'selected'; ?>>Aston Martin</option>
        <option value="Audi" <?php if ($brand == 'Audi') echo 'selected'; ?>>Audi</option>
        <option value="Bentley" <?php if ($brand == 'Bentley') echo 'selected'; ?>>Bentley</option>
        <option value="BMW" <?php if ($brand == 'BMW') echo 'selected'; ?>>BMW</option>
        <option value="Cadillac" <?php if ($brand == 'Cadillac') echo 'selected'; ?>>Cadillac</option>
        <option value="Chery" <?php if ($brand == 'Chery') echo 'selected'; ?>>Chery</option>
        <option value="Chevrolet" <?php if ($brand == 'Chevrolet') echo 'selected'; ?>>Chevrolet</option>
        <option value="Chrysler" <?php if ($brand == 'Chrysler') echo 'selected'; ?>>Chrysler</option>
        <option value="Citroen" <?php if ($brand == 'Citroen') echo 'selected'; ?>>Citroen</option>
        <option value="Dacia" <?php if ($brand == 'Dacia') echo 'selected'; ?>>Dacia</option>
        <option value="Daewoo" <?php if ($brand == 'Daewoo') echo 'selected'; ?>>Daewoo</option>
        <option value="Daihatsu" <?php if ($brand == 'Daihatsu') echo 'selected'; ?>>Daihatsu</option>
        <option value="Dodge" <?php if ($brand == 'Dodge') echo 'selected'; ?>>Dodge</option>
        <option value="DS Automobiles" <?php if ($brand == 'DS Automobiles') echo 'selected'; ?>>DS Automobiles</option>
        <option value="Ferrari" <?php if ($brand == 'Ferrari') echo 'selected'; ?>>Ferrari</option>
        <option value="Fiat" <?php if ($brand == 'Fiat') echo 'selected'; ?>>Fiat</option>
        <option value="Ford" <?php if ($brand == 'Ford') echo 'selected'; ?>>Ford</option>
        <option value="GAS" <?php if ($brand == 'GAS') echo 'selected'; ?>>GAS</option>
        <option value="Geely" <?php if ($brand == 'Geely') echo 'selected'; ?>>Geely</option>
        <option value="Honda" <?php if ($brand == 'Honda') echo 'selected'; ?>>Honda</option>
        <option value="Hyundai" <?php if ($brand == 'Hyundai') echo 'selected'; ?>>Hyundai</option>
        <option value="Infiniti" <?php if ($brand == 'Infiniti') echo 'selected'; ?>>Infiniti</option>
        <option value="Isuzu" <?php if ($brand == 'Isuzu') echo 'selected'; ?>>Isuzu</option>
        <option value="Jaguar" <?php if ($brand == 'Jaguar') echo 'selected'; ?>>Jaguar</option>
        <option value="Kia" <?php if ($brand == 'Kia') echo 'selected'; ?>>Kia</option>
        <option value="Lada" <?php if ($brand == 'Lada') echo 'selected'; ?>>Lada</option>
        <option value="Lamborghini" <?php if ($brand == 'Lamborghini') echo 'selected'; ?>>Lamborghini</option>
        <option value="Lancia" <?php if ($brand == 'Lancia') echo 'selected'; ?>>Lancia</option>
        <option value="Lexus" <?php if ($brand == 'Lexus') echo 'selected'; ?>>Lexus</option>
        <option value="Lincoln" <?php if ($brand == 'Lincoln') echo 'selected'; ?>>Lincoln</option>
        <option value="Lotus" <?php if ($brand == 'Lotus') echo 'selected'; ?>>Lotus</option>
        <option value="Maserati" <?php if ($brand == 'Maserati') echo 'selected'; ?>>Maserati</option>
        <option value="Mazda" <?php if ($brand == 'Mazda') echo 'selected'; ?>>Mazda</option>
        <option value="McLaren" <?php if ($brand == 'McLaren') echo 'selected'; ?>>McLaren</option>
        <option value="Mercedes-Benz" <?php if ($brand == 'Mercedes-Benz') echo 'selected'; ?>>Mercedes-Benz</option>
        <option value="MG" <?php if ($brand == 'MG') echo 'selected'; ?>>MG</option>
        <option value="MINI" <?php if ($brand == 'MINI') echo 'selected'; ?>>MINI</option>
        <option value="Mitsubishi" <?php if ($brand == 'Mitsubishi') echo 'selected'; ?>>Mitsubishi</option>
        <option value="Morris" <?php if ($brand == 'Morris') echo 'selected'; ?>>Morris</option>
        <option value="Nissan" <?php if ($brand == 'Nissan') echo 'selected'; ?>>Nissan</option>
        <option value="Opel" <?php if ($brand == 'Opel') echo 'selected'; ?>>Opel</option>
        <option value="Peugeot" <?php if ($brand == 'Peugeot') echo 'selected'; ?>>Peugeot</option>
        <option value="Pontiac" <?php if ($brand == 'Pontiac') echo 'selected'; ?>>Pontiac</option>
        <option value="Porsche" <?php if ($brand == 'Porsche') echo 'selected'; ?>>Porsche</option>
        <option value="Proton" <?php if ($brand == 'Proton') echo 'selected'; ?>>Proton</option>
        <option value="Renault" <?php if ($brand == 'Renault') echo 'selected'; ?>>Renault</option>
        <option value="Rolls-Royce" <?php if ($brand == 'Rolls-Royce') echo 'selected'; ?>>Rolls-Royce</option>
        <option value="Rover" <?php if ($brand == 'Rover') echo 'selected'; ?>>Rover</option>
        <option value="Saab" <?php if ($brand == 'Saab') echo 'selected'; ?>>Saab</option>
        <option value="Seat" <?php if ($brand == 'Seat') echo 'selected'; ?>>Seat</option>
        <option value="Skoda" <?php if ($brand == 'Skoda') echo 'selected'; ?>>Skoda</option>
        <option value="Smart" <?php if ($brand == 'Smart') echo 'selected'; ?>>Smart</option>
        <option value="Subaru" <?php if ($brand == 'Subaru') echo 'selected'; ?>>Subaru</option>
        <option value="Suzuki" <?php if ($brand == 'Suzuki') echo 'selected'; ?>>Suzuki</option>
        <option value="Tata" <?php if ($brand == 'Tata') echo 'selected'; ?>>Tata</option>
        <option value="Tesla" <?php if ($brand == 'Tesla') echo 'selected'; ?>>Tesla</option>
        <option value="Tofas" <?php if ($brand == 'Tofas') echo 'selected'; ?>>Tofas</option>
        <option value="Toyota" <?php if ($brand == 'Toyota') echo 'selected'; ?>>Toyota</option>
        <option value="Triumph" <?php if ($brand == 'Triumph') echo 'selected'; ?>>Triumph</option>
        <option value="Vauxhall" <?php if ($brand == 'Vauxhall') echo 'selected'; ?>>Vauxhall</option>
        <option value="Volkswagen" <?php if ($brand == 'Volkswagen') echo 'selected'; ?>>Volkswagen</option>
        <option value="Volvo" <?php if ($brand == 'Volvo') echo 'selected'; ?>>Volvo</option>
    </select>
</div>


            <!-- Dropdown for Model -->
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

            // Check if the selected brand has models, then populate the dropdown
            if (isset($carModels[$brand])) {
                foreach ($carModels[$brand] as $modelOption) {
                    $selected = ($model == $modelOption) ? 'selected' : '';
                    echo "<option value='$modelOption' $selected>$modelOption</option>";
                }
            }
        }
        ?>
    </select>
</div>


            <div style="text-align: center;">
                <input type="submit" value="Submit" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
            </div>
        </form>
    </div>
</body>
</html>
