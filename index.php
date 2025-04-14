<?php
session_start();
include('db_connection.php');

// Function to get the current timestamp in Turkey's timezone
function getCurrentTimestamp() {
    // Set the timezone to Turkey's timezone (UTC+3)
    date_default_timezone_set('Europe/Istanbul');
    
    // Return the formatted date in "Y-m-d H:i:s"
    return date("Y-m-d H:i:s");
}

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

// Check if we are editing an existing car
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM cars WHERE id = '$edit_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_name = $row['customer_name'];
        $plate = $row['plate'];
        $chasis = $row['chasis'];
        $brand = $row['brand'];
        $year = $row['year'];
        $model = $row['model'];
        $km_mile = $row['km_mile'];
        $accident_visual = $row['accident_visual'];
        $accident_tramer = $row['accident_tramer'];
        $msf = $row['msf'];
        $dsf = $row['dsf'];
        $gsf = $row['gsf'];
        $package = $row['package'];
        $color = $row['color'];
        $engine = $row['engine'];
        $gear = $row['gear'];
        $fuel = $row['fuel'];
        $expense_detail = $row['expense_detail'];
        $current_expense = $row['current_total_expense'];
        $image = $row['image'];
        $image2 = $row['image2'];
        $image3 = $row['image3'];
        $image4 = $row['image4'];
        
        
        // Now, extract km_value and km_unit from km_mile (like "25 KM" or "25 MILE")
        $km_value = ''; // Default empty value for numeric part
        $km_unit = ''; // Default empty value for unit (KM or MILE)
        
        if (!empty($km_mile)) {
            // Split km_mile into numeric value and unit
            $parts = explode(' ', trim($km_mile));
            $km_value = isset($parts[0]) ? $parts[0] : '';  // The numeric part (e.g., 25)
            $km_unit = isset($parts[1]) ? strtoupper($parts[1]) : '';  // The unit part (KM or MILE)
            }
    }
} else {
    // Default values for a new car entry
    $customer_name = $plate = $chasis = $brand = $year = $model = $km_mile = $accident_visual = $accident_tramer = $msf = $dsf = $gsf = $package = $color = $engine = $gear = $fuel = $expense_detail = $current_expense = $image = $image2 = $image3 = $image4 = '';
        $km_value = ''; // Default empty value for numeric part
        $km_unit = '';  // Default empty value for unit (KM or MILE)
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $plate = $_POST['plate'];
    $chasis = $_POST['chasis'];
    if ($_POST['brand'] === 'other' && !empty($_POST['other_brand'])) {
        $brand = trim($_POST['other_brand']); // Use the custom value
    } else {
        $brand = $_POST['brand']; // Use the selected value
    }

    $year = $_POST['year'];

    // Handle custom model
    if ($_POST['model'] === 'other' && !empty($_POST['other_model'])) {
        $model = trim($_POST['other_model']); // Use the custom model value
    } else {
        $model = $_POST['model']; // Use the selected model
    }


    $km_mile = $_POST['km_mile'];
    $accident_visual = $_POST['accident_visual'];
    $accident_tramer = $_POST['accident_tramer'];
    $msf = $_POST['msf'];
    $dsf = $_POST['dsf'];
    $gsf = $_POST['gsf'];
    $package = $_POST['package'];
    $color = $_POST['color'];
    $engine = $_POST['engine'];
    $gear = $_POST['gear'];
    $fuel = $_POST['fuel'];
    $expense_detail = $_POST['expense_detail'];
    $current_expense = $_POST['current_expense'];

 
    // Handle file upload for images
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image;
        move_uploaded_file($image_tmp, $image_folder);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image = $row['image'];
    }

    // Handle file upload for second image
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] == 0) {
        $image2 = $_FILES['image2']['name'];
        $image_tmp2 = $_FILES['image2']['tmp_name'];
        $image_folder2 = 'uploads/' . $image2;
        move_uploaded_file($image_tmp2, $image_folder2);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image2 = $row['image2'];
    }

    // Handle file upload for third image
    if (isset($_FILES['image3']) && $_FILES['image3']['error'] == 0) {
        $image3 = $_FILES['image3']['name'];
        $image_tmp3 = $_FILES['image3']['tmp_name'];
        $image_folder3 = 'uploads/' . $image3;
        move_uploaded_file($image_tmp3, $image_folder3);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image3 = $row['image3'];
    }

    // Handle file upload for fourth image
    if (isset($_FILES['image4']) && $_FILES['image4']['error'] == 0) {
        $image4 = $_FILES['image4']['name'];
        $image_tmp4 = $_FILES['image4']['tmp_name'];
        $image_folder4 = 'uploads/' . $image4;
        move_uploaded_file($image_tmp4, $image_folder4);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image4 = $row['image4'];
    }

    // Get the current timestamp for created_at and updated_at
    $timestamp = getCurrentTimestamp();

    if (isset($_GET['edit_id'])) {
        // Update the car details
        $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', chasis = '$chasis', brand = '$brand', year = '$year', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', gsf = '$gsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense', image = '$image', image2 = '$image2', image3 = '$image3', image4 = '$image4', updated_at = '$timestamp', updated_by = '{$_SESSION['username']}' WHERE id = '$edit_id'";

        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate, $chasis, $brand, $year, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $gsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense, $image, $image2, $image3, $image4);

            // ✅ **ADD REDIRECT BASED ON USER ROLE HERE**
            if ($_SESSION['role'] === 'admin') {
                header("Location: display.php");
            } else {
                header("Location: userdisplay.php");
            }
            exit(); // Stop further execution
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert new car data
        $sql = "INSERT INTO cars (customer_name, plate, chasis, brand, year, model, km_mile, accident_visual, accident_tramer, msf, dsf, gsf, package, color, engine, gear, fuel, expense_detail, current_total_expense, image, image2, image3, image4, created_at, created_by) 
        VALUES ('$customer_name', '$plate', '$chasis', '$brand', '$year', '$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf', '$gsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense', '$image', '$image2', '$image3', '$image4', '$timestamp', '{$_SESSION['username']}')";

        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate, $chasis, $brand, $year, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $gsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense);

            // ✅ **ADD REDIRECT BASED ON USER ROLE HERE**
            if ($_SESSION['role'] === 'admin') {
                header("Location: display.php");
            } else {
                header("Location: userdisplay.php");
            }
            exit(); // Stop further execution
        } else {
            echo "Error: " . $conn->error;
        }
    }


}



$conn->close();
?>



<body>

<header>
    <h1>SERHAN KOMBOS OTOMOTIV</h1>
    <h2><?php echo isset($_GET['edit_id']) ? 'Update Database' : 'Car Database'; ?></h2>
</header>


<div class="container">

    <form action="index.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>" method="post" enctype="multipart/form-data">
        
        <!-- Müşteri Adi | Customer Name -->
        <div class="form-group">
            <label for="customer_name">Müşteri Adi | Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo isset($customer_name) ? htmlspecialchars($customer_name) : ''; ?>" required />
        </div>

        <!-- Plaka | Plate -->
<div class="form-group">
    <label for="plate">Plaka | Plate:</label>
    <input 
        type="text" 
        id="plate" 
        name="plate" 
        value="<?php echo isset($plate) ? htmlspecialchars($plate) : ''; ?>" 
        required 
        placeholder="VV 700" 
    />
    <small id="plateError" style="color: red; display: none;">Please enter the plate in the format "XX 000" (two letters and three digits).</small>
</div>


        <!-- Şasi Numarasi | Chasis Number -->
       <?php
    $chasis = isset($chasis) ? $chasis : ''; // Default empty string if $chasis is not set

    // Extract numeric price and currency separately for msf
    $chasis_parts = explode(" ", $chasis);
    $chasis_currency = isset($chasis_parts[0]) ? $chasis_parts[0] : ""; // Corrected to get currency
    $chasis_value = isset($chasis_parts[1]) ? $chasis_parts[1] : ""; // Corrected to get the chasis value
?>
<!-- Chassis Field (Initially Hidden) -->
<div class="form-group" id="chasis_container" style="display: none;">
    <label for="chasis">Şasi Numarasi | Chasis Number:</label>
    <div style="display: flex; gap: 10px; align-items: center;">
        <?php
            // Extract chassis prefix (first 3 characters) and chassis number (rest)
            $chasis_value = htmlspecialchars($chasis ?? '');
            $chasis_prefix = substr($chasis_value, 0, 3); // First 3 characters (Prefix)
            $chasis_number = substr($chasis_value, 3); // Remaining part (Number)
        ?>

        <!-- Chassis Prefix Dropdown -->
        <select id="currency_selector5" name="currency_selector5" onchange="updateChasisField()" >
            <option value="" disabled <?php echo empty($chasis_prefix) ? 'selected' : ''; ?>>Select</option>
            <option value="WDB" <?php echo ($chasis_prefix == 'WDB') ? 'selected' : ''; ?>>WDB</option>
            <option value="WDC" <?php echo ($chasis_prefix == 'WDC') ? 'selected' : ''; ?>>WDC</option>
            <option value="WDD" <?php echo ($chasis_prefix == 'WDD') ? 'selected' : ''; ?>>WDD</option>
            <option value="WDF" <?php echo ($chasis_prefix == 'WDF') ? 'selected' : ''; ?>>WDF</option>
            <option value="W1K" <?php echo ($chasis_prefix == 'W1K') ? 'selected' : ''; ?>>W1K</option>
            <option value="W1N" <?php echo ($chasis_prefix == 'W1N') ? 'selected' : ''; ?>>W1N</option>
            <option value="W1T" <?php echo ($chasis_prefix == 'W1T') ? 'selected' : ''; ?>>W1T</option>
            <option value="W1V" <?php echo ($chasis_prefix == 'W1V') ? 'selected' : ''; ?>>W1V</option>
            <option value="W1W" <?php echo ($chasis_prefix == 'W1W') ? 'selected' : ''; ?>>W1W</option>
            <option value="W1X" <?php echo ($chasis_prefix == 'W1X') ? 'selected' : ''; ?>>W1X</option>
            <option value="W1Y" <?php echo ($chasis_prefix == 'W1Y') ? 'selected' : ''; ?>>W1Y</option>
            <option value="W1J" <?php echo ($chasis_prefix == 'W1J') ? 'selected' : ''; ?>>W1J</option>
            <option value="4JG" <?php echo ($chasis_prefix == '4JG') ? 'selected' : ''; ?>>4JG</option>
            <option value="55S" <?php echo ($chasis_prefix == '55S') ? 'selected' : ''; ?>>55S</option>
            <option value="1MB" <?php echo ($chasis_prefix == '1MB') ? 'selected' : ''; ?>>1MB</option>
            <option value="2B1" <?php echo ($chasis_prefix == '2B1') ? 'selected' : ''; ?>>2B1</option>
        </select>

        <!-- Chassis Code Input -->
        <input type="text" id="chasis_currency" name="chasis_currency" placeholder="Enter Chasis Code" 
            value="<?php echo htmlspecialchars($chasis_number); ?>" oninput="updateChasisField()"  />

        <!-- Hidden Input Field that Stores Combined Value -->
        <input type="hidden" id="chasis" name="chasis" value= "<?php echo htmlspecialchars($chasis_value); ?>" />
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const brandDropdown = document.getElementById("brand");
        const modelDropdown = document.getElementById("model");
        const chasisContainer = document.getElementById("chasis_container");
        const chassisSelector = document.getElementById("currency_selector5");
        const chassisInput = document.getElementById("chasis_currency");
        const chassisHidden = document.getElementById("chasis");

        function toggleChasisField() {
            if (!brandDropdown || !chasisContainer) {
                console.error("Brand dropdown or chassis container not found!");
                return;
            }

            var selectedBrand = brandDropdown.value.trim();
            if (selectedBrand === "Mercedes-Benz") {
                chasisContainer.style.display = "flex";
            } else {
                chasisContainer.style.display = "none";
                chassisHidden.value = ""; // Clear chassis value when brand is not Mercedes-Benz
            }
        }

        function updateChasisField() {
    const prefix = chassisSelector.value.trim();
    const number = chassisInput.value.trim();

    // Check if both prefix and number are available
    if (prefix && number) {
        chassisHidden.value = prefix + " " + number;  // Adding space between prefix and number
    } else {
        chassisHidden.value = "";  // Clear if either is empty
    }
}


        // Ensure chassis is updated when user changes input
        if (chassisSelector && chassisInput) {
            chassisSelector.addEventListener("change", updateChasisField);
            chassisInput.addEventListener("input", updateChasisField);
        }

        // Ensure the chassis field is visible if it's already filled (editing)
        if (chassisHidden.value !== "") {
            chasisContainer.style.display = "flex";
        }

        // Ensure visibility on brand change
        if (brandDropdown) {
            brandDropdown.addEventListener("change", toggleChasisField);
            toggleChasisField();
        }
    });
</script>

<div class="form-group">
    <label for="brand">Marka | Brand:</label>
    <?php
    $brands = ["Acura", "Alfa Romeo", "Audi", "BMW", "Chevrolet", "Chrysler", "Citroen", "Dodge", "Fiat", "Ford", "Honda", "Hyundai", "Infiniti", "Jaguar", "Kia", "Lexus", "Maybach", "Mazda", "Mercedes-Benz", "Nissan", "Peugeot", "Renault", "Subaru", "Toyota", "Volkswagen"];
    $isBrandInList = in_array($brand, $brands);
    $isCustomBrand = !$isBrandInList && !empty($brand);
    ?>
    <select id="brand" name="brand" onchange="checkOtherBrand(); updateModels();">
        <option value="" disabled <?= empty($brand) ? 'selected' : '' ?>>Select</option>
        <?php foreach ($brands as $b): ?>
            <option value="<?= $b ?>" <?= ($brand === $b) ? 'selected' : '' ?>><?= $b ?></option>
        <?php endforeach; ?>
        <option value="other" <?= $isCustomBrand ? 'selected' : '' ?>>Other</option>
    </select>

    <input type="text" name="other_brand" id="otherBrandInput" placeholder="Enter brand..." 
           value="<?= $isCustomBrand ? htmlspecialchars($brand) : '' ?>" 
           style="<?= $isCustomBrand ? 'display:block;' : 'display:none;' ?>">
</div>

<div class="form-group">
    <label for="model">Modeli | Model:</label>
    <?php
    // Check if model is custom (i.e., the value is "other")
    $isCustomModel = !empty($model) && $model === 'other';
    ?>
    <select id="model" name="model" onchange="checkOtherModel();">
        <option value="" disabled <?= empty($model) ? 'selected' : '' ?>>Select</option>
        <option value="other" <?= $model === 'other' ? 'selected' : '' ?>>Other</option>
    </select>

    <input type="text" name="other_model" id="otherModelInput" placeholder="Enter model..." 
           value="<?= $isCustomModel ? htmlspecialchars($model) : '' ?>" 
           style="<?= $isCustomModel ? 'display:block;' : 'display:none;' ?>">
</div>


        <!-- Motor | Engine -->
        <div class="form-group">
            <label for="engine">Motor | Engine:</label>
            <input type="text" id="engine" name="engine" value="<?php echo isset($engine) ? htmlspecialchars($engine) : ''; ?>" required />
        </div>

        <!-- Paket | Package -->
        <div class="form-group">
            <label for="package">Paket | Package:</label>
            <input type="text" id="package" name="package" value="<?php echo isset($package) ? htmlspecialchars($package) : ''; ?>" required />
        </div>

        <!-- Renk | Color -->
        <div class="form-group">
            <label for="color">Renk | Color:</label>
            <input type="text" id="color" name="color" value="<?php echo isset($color) ? htmlspecialchars($color) : ''; ?>" required />
        </div>

        <!-- Üretim Yili | Year of Manufacture -->
        <div class="form-group">
            <label for="year">Üretim Yili | Year of Manufacture:</label>
            <select id="year" name="year" required>
                <option value="" disabled <?php echo empty($year) ? 'selected' : ''; ?>>Select</option>
                <?php
                    $yearOptions = ["2025", "2024", "2023", "2022", "2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014", 
                    "2013", "2012", "2011", "2010", "2009", "2008", "2007", "2006", "2005", "2004", "2003", "2002", 
                    "2001", "2000", "1999", "1998", "1997", "1996", "1995", "1994", "1993", "1992", "1991", "1990", "1989"];
                    foreach ($yearOptions as $yearOption) {
                    $selected = ($year == $yearOption) ? 'selected' : '';
                    echo "<option value='$yearOption' $selected>$yearOption</option>";
                }
                ?>
            </select>
        </div>

   <div class="form-group">
        <label for="km_mile">Mil/KM | Mile/KM:</label>
        <div style="display: flex; gap: 10px; align-items: center;">
            <!-- Input for the numeric value -->
            <input type="number" id="km_mile_value" name="km_mile_value" step="0.01" placeholder="Enter value" value="<?php echo htmlspecialchars($km_value); ?>" required />

            <!-- Dropdown to select either KM or MILE -->
            <select id="currency_selector3" name="currency_selector3" required>
                <option value="" disabled <?php echo empty($km_unit) ? 'selected' : ''; ?>>Select</option>
                <option value="KM" <?php echo ($km_unit === 'KM') ? 'selected' : ''; ?>>KM</option>
                <option value="MILE" <?php echo ($km_unit === 'MILE') ? 'selected' : ''; ?>>MILE</option>
            </select>

            <!-- Hidden input to store the combined value -->
            <input type="hidden" id="km_mile" name="km_mile" value="<?php echo htmlspecialchars($km_mile); ?>" />
        </div>
    </div>

        <!-- Yakit | Fuel -->
        <div class="form-group">
            <label for="fuel">Yakit | Fuel:</label>
            <select id="fuel" name="fuel" required>
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

        <!-- Vites | Gear -->
        <div class="form-group">
            <label for="gear">Vites | Gear:</label>
            <select id="gear" name="gear" required>
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

        <!-- Kaza Görsel | Accident Visual -->
        <div class="form-group">
            <label for="accident_visual">Kaza Görsel | Accident Visual:</label>
            <select id="accident_visual" name="accident_visual" required onchange="toggleOtherField()">
                <option value="" disabled <?php echo empty($accident_visual) ? 'selected' : ''; ?>>Select</option>
                <?php
                $accident_visualOptions = ["CRITICAL DAMAGE", "MINOR DAMAGE", "NO VISUAL DAMAGE"];
                foreach ($accident_visualOptions as $accident_visualOption) {
                    $selected = ($accident_visual == $accident_visualOption) ? 'selected' : '';
                    echo "<option value='$accident_visualOption' $selected>$accident_visualOption</option>";
                }
                ?>
                <option value="other" <?php echo ($accident_visual == 'other') ? 'selected' : ''; ?>>Other</option>
            </select>
            <input type="text" id="other_accident_visual" name="other_accident_visual" placeholder="Please specify" style="display: none; margin-top: 10px;" value="<?php echo ($accident_visual == 'other') ? htmlspecialchars($other_accident_visual) : ''; ?>" />
        </div>

        <!-- Kaza Tramer | Accident Tramer -->
        <div class="form-group">
            <label for="accident_tramer">Kaza Tramer | Accident Tramer:</label>
            <input type="text" id="accident_tramer" name="accident_tramer" value="<?php echo isset($accident_tramer) ? htmlspecialchars($accident_tramer) : ''; ?>" required />
        </div>

        <!-- Masraf Detayi | Expense Detail -->
        <div class="form-group">
            <label for="expense_detail">Masraf Detayi | Expense Detail:</label>
            <input type="text" id="expense_detail" name="expense_detail" value="<?php echo isset($expense_detail) ? htmlspecialchars($expense_detail) : ''; ?>" required />
        </div>

        <!-- Aktuel Masraf Toplami | Current Total Expense -->
        <div class="form-group">
            <label for="current_expense">Aktuel Masraf Toplami | Current Total Expense:</label>
            <input type="number" id="current_expense" name="current_expense" value="<?php echo isset($current_expense) ? htmlspecialchars($current_expense) : ''; ?>" required />
        </div>
        <?php
                // Initialize the variables $msf and $dsf with some default value
                $msf = isset($msf) ? $msf : ''; // Default empty string if $msf is not set
                $dsf = isset($dsf) ? $dsf : ''; // Default empty string if $dsf is not set
                $gsf = isset($gsf) ? $gsf : ''; // Default empty string if $gsf is not set

                // Extract numeric price and currency separately for msf
                $msf_parts = explode(" ", $msf);
                $msf_value = isset($msf_parts[0]) ? $msf_parts[0] : "";
                $msf_currency = isset($msf_parts[1]) ? $msf_parts[1] : "";

                // Extract numeric price and currency separately for dsf
                $dsf_parts = explode(" ", $dsf);
                $dsf_value = isset($dsf_parts[0]) ? $dsf_parts[0] : "";
                $dsf_currency = isset($dsf_parts[1]) ? $dsf_parts[1] : "";

                // Extract numeric price and currency separately for gsf
                $gsf_parts = explode(" ",$gsf);
                $gsf_value = isset($gsf_parts[0]) ? $gsf_parts[0] : "";
                $gsf_currency = isset($gsf_parts[1]) ? $gsf_parts[1] : "";

            
            ?>

           <!-- Müşteri Satiş Fiyati (MSF) -->
           <div class="form-group">
                <label for="msf">Müşteri Satiş Fiyati (MSF):</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="number" id="msf_value" step="0.01" placeholder="Enter price" value="<?php echo htmlspecialchars($msf_value); ?>" required />
                    <select id="currency_selector1" onchange="updateMSF()" required>
                        <option value="" disabled <?php echo empty($msf_currency) ? 'selected' : ''; ?>>Select</option>
                        <option value="STG" <?php echo ($msf_currency == 'STG') ? 'selected' : ''; ?>>£ STG</option>
                        <option value="EUR" <?php echo ($msf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                        <option value="TL" <?php echo ($msf_currency == 'TL') ? 'selected' : ''; ?>>₺ TL</option>
                    </select>
                    <input type="hidden" id="msf" name="msf" value="<?php echo htmlspecialchars($msf); ?>" />
                </div>
            </div>

        <!-- Düsünülen Satis Fiyati (DSF) -->
        <div class="form-group">
            <label for="dsf">Düsünülen Satis Fiyati (DSF):</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="number" id="dsf_value" step="0.01" placeholder="Enter price" value="<?php echo htmlspecialchars($dsf_value); ?>" required />
                <select id="currency_selector2" onchange="updateDSF()" required>
                    <option value="" disabled <?php echo empty($dsf_currency) ? 'selected' : ''; ?>>Select</option>
                    <option value="STG" <?php echo ($msf_currency == 'STG') ? 'selected' : ''; ?>>£ STG</option>
                    <option value="EUR" <?php echo ($dsf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                    <option value="TL" <?php echo ($dsf_currency == 'TL') ? 'selected' : ''; ?>>₺ TL</option>
                </select>
                <input type="hidden" id="dsf" name="dsf" value="<?php echo htmlspecialchars($dsf); ?>" />
            </div>
        </div>

        <!-- Gerçekleşen Satiş Fiyati (GSF) -->
        <div class="form-group">
            <label for="gsf">Gerçekleşen Satiş Fiyati (GSF):</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="number" id="gsf_value" step="0.01" placeholder="(optional)" value="<?php echo htmlspecialchars($gsf_value); ?>"  />
                <select id="currency_selector4" onchange="updateGSF()" >
                    <option value="" disabled <?php echo empty($gsf_currency) ? 'selected' : ''; ?>>Select</option>
                    <option value="STG" <?php echo ($msf_currency == 'STG') ? 'selected' : ''; ?>>£ STG</option>
                    <option value="EUR" <?php echo($gsf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                    <option value="TL" <?php echo ($gsf_currency == 'TL') ? 'selected' : ''; ?>>₺ TL</option>
                </select>
                <input type="hidden" id="gsf" name="gsf" value="<?php echo htmlspecialchars($gsf); ?>" />
            </div>
        </div>

        <!-- Car Images -->
        <!-- File Upload -->
<!-- Car Image 1 -->
<div class="form-group">
    <label for="image">Car Image:</label>
    <input 
        type="file" 
        id="image" 
        name="image" 
        <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> 
        onchange="checkImageOrientation(this, 'image-warning')" 
    />
    <?php
    if (!empty($image) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image: <img src='uploads/$image' alt='Car Image' width='100' /></p>";
    }
    ?>
    <span id="image-warning" style="color: red; display: none;"></span>
</div>

<!-- Car Image 2 -->
<div class="form-group">
    <label for="image2">Car Image2:</label>
    <input 
        type="file" 
        id="image2" 
        name="image2" 
        <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> 
        onchange="checkImageOrientation(this, 'image2-warning')" 
    />
    <?php
    if (!empty($image2) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image2: <img src='uploads/$image2' alt='Car Image2' width='100' /></p>";
    }
    ?>
    <span id="image2-warning" style="color: red; display: none;"></span>
</div>

<!-- Car Image 3 -->
<div class="form-group">
    <label for="image3">Car Image3:</label>
    <input 
        type="file" 
        id="image3" 
        name="image3" 
        <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> 
        onchange="checkImageOrientation(this, 'image3-warning')" 
    />
    <?php
    if (!empty($image3) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image3: <img src='uploads/$image3' alt='Car Image3' width='100' /></p>";
    }
    ?>
    <span id="image3-warning" style="color: red; display: none;"></span>
</div>

<!-- Car Image 4 -->
<div class="form-group">
    <label for="image4">Car Image4:</label>
    <input 
        type="file" 
        id="image4" 
        name="image4" 
        <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> 
        onchange="checkImageOrientation(this, 'image4-warning')" 
    />
    <?php
    if (!empty($image4) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image4: <img src='uploads/$image4' alt='Car Image4' width='100' /></p>";
    }
    ?>
    <span id="image4-warning" style="color: red; display: none;"></span>
</div>


<script>
    function checkImageOrientation(input, warningId) {
        var file = input.files[0];
        var img = new Image();

        img.onload = function() {
            if (img.width > img.height) {
                // Image is in landscape orientation
                document.getElementById(warningId).style.display = "none";
                input.setCustomValidity(''); // Clear any previous custom validity
            } else {
                // Image is not in landscape orientation
                document.getElementById(warningId).style.display = "block";
                input.setCustomValidity("Image must be in landscape orientation.");
            }
        };

        img.src = URL.createObjectURL(file);
    }
</script>


        <button type="submit"><?php echo isset($_GET['edit_id']) ? 'Update Car' : 'Add Car'; ?></button>
    </form>
</div>


<div class="action-links">
       <a href="home.php">Back To Home</a>
        <a href="display.php">View Submitted Cars</a>
     
    </div>



<footer>
    <p>&copy; 2025 Car Database System. All rights reserved.</p>
</footer>
<script>
    const carModels = {
    "Chrysler": ["300 C", "300 M", "Concorde", "Crossfire", "LHS", "Neon", "PT Cruiser", "Sebring", "Stratus"],

    "Audi": [
                "100", "80", "A1", "A2", "A3", "A3 Cabriolet", "A4", "A4 Allroad", 
                "A4 Avant", "A4 Cabriolet", "A5", "A5 Avant", "A5 Cabriolet", 
                "A6 Allroad", "A6 Avant", "A6 e-tron Avant", "A6 Saloon", "A6 Unspecified",
                "A7", "A8", "Allroad", "Cabriolet", "Coupe", "e-tron", "e-tron GT", 
                "e-tron S", "Q2", "Q3", "Q4 e-tron", "Q5", "Q6 e-tron", "Q7", "Q8", 
                "Q8 e-tron", "quattro", "R8", "RS3", "RS4", "RS4 Avant", 
                "RS4 Cabriolet", "RS5", "RS6", "RS6 Avant", "RS7", "RS e-tron GT", 
                "RS Q3", "RSQ8", "S1", "S3", "S4", "S4 Avant", "S4 Cabriolet", 
                "S5", "S5 Avant", "S6 Avant", "S6 e-tron Avant", "S6 Saloon", "S7", 
                "S8", "S e-tron GT", "SQ2", "SQ5", "SQ6 e-tron", "SQ7", "SQ8", 
                "SQ8 e-tron", "TT", "TT RS", "TTS"],


    "Alfa Romeo":["156 Sportwagon","159","159 Sportwagon","164","166","2000","4C","Alfasud","Brera","Giulia","Giulietta","GT","GTV","Junior","MiTo","Spider","Stelvio","Tonale"],

    "BMW": ["1 Series", "2 Series", "2 Series Active Tourer", "2 Series Gran Coupe", "2 Series Gran Tourer", "3 Series", "3 Series Gran Turismo", "4 Series", "4 Series Gran Coupe", "5 Series", "5 Series Gran Turismo", "6 Series", "6 Series Gran Turismo", "7 Series", "8 Series", "8 Series Gran Coupe", "Alpina B10", "Alpina B3", "Alpina B4 Gran Coupe", "Alpina B5", "Alpina B6", "Alpina B8 Gran Coupe", "Alpina D3", "Alpina D4", "Alpina D4 Gran Coupe", "Alpina D5", "Alpina Roadster", "Alpina Unspecified Models", "Alpina XD3",
            "i3", "i4", "i5", "i7", "i8", "Isetta", "iX", "iX1", "iX2", "iX3", "M2", "M3", "M4", "M5", "M6", "M6 Gran Coupe", "M8", "M8 Gran Coupe", "X1", "X2", "X3", "X4", "X5", "X6", "X7", "XM", "Z3", "Z4", "Z4 M", "Z8"],

        "Chevrolet": [
                    "Astro", "Aveo", "Belair", "C10", "Camaro", "Captiva", "Corvette", 
                    "Corvette Stingray", "Cruze", "Kalos", "Lacetti", "Matiz", "Orlando", 
                    "Silverado", "Spark", "SSR", "Suburban", "Tacuma", "Trax"],
        "Citroen": [
                    "2 CV", "Ami", "AX", "Berlingo", "BX", "C1", "C2", "C3", "C3 Aircross", 
                    "C3 Picasso", "C3 Pluriel", "C4", "C4 Cactus", "C4 Picasso", 
                    "C4 SpaceTourer", "C4 X", "C5", "C5 Aircross", "C5 X", "C6", "C8", 
                    "C-Crosser", "C-Zero", "Dispatch", "DS3", "DS3 Cabrio", "DS4", "DS5", 
                    "e-Berlingo", "e-C3", "e-C3 Aircross", "e-C4", "e-C4 X", 
                    "e-SpaceTourer", "Grand C4 Picasso", "Grand C4 SpaceTourer", "Holidays", 
                    "Nemo Multispace", "Relay", "Saxo", "SpaceTourer", "Xantia", "Xsara", 
                    "Xsara Picasso"],


            "Maybach": ["57", "62"],

            "Acura": ["Integra", "RSX"],

            "Peugeot": ["1007", "106", "107", "108", "2008", "205", "206", "206 CC", "206 SW", "207", "207 CC", "207 SW", "208", "3008", "306", "307", "307 CC", "307 SW", "308", "308 CC", "308 SW", "309", "4007", "406", "407", "407 SW", "408",
                "5008", "508", "508 SW", "807", "Bipper Tepee", "Boxer", "E-2008", "E-208", "E-3008", "E-308", "E-308 SW",
                "E-5008", "e-Partner", "e-Rifter", "e-Traveller", "Expert", "Expert Tepee", "Horizon", "iOn", "Partner", "Partner Tepee", "RCZ", "Rifter", "Traveller"],

            "Renault": ["Laguna", "Master", "Megane", "Megane E-Tech", "Modus", "Rafale", "Scenic", "Scenic E-Tech", "Scenic RX4", "Scenic Xmod", "Spider", "Symbioz", "Trafic", "Twingo", "Twizy", "Wind", "Zoe"],

            "Subaru": ["BRZ", "Crosstrek", "Exiga", "Forester", "Impreza", "Justy", "Legacy", "Levorg", "Outback", "Solterra", "Tribeca", "WRX STI", "XT", "XV"],

            "Mazda": ["323", "626", "B2500", "Bongo", "BT-50", "CX-3", "CX-30", "CX-5", "CX-60", "CX-7", "CX-80", "Demio", "Eunos", "Mazda2", "Mazda2 HYBRID", "Mazda3", "Mazda5", "Mazda6", "MPV", "MX-30", "MX-5", "MX-5 RF", "MX-6", "RX-7", "RX-8"],

            "Lexus": ["CT", "ES", "GS", "GS F", "GX", "IS", "IS F", "LBX", "LC", "LFA", "LM", "LS", "LX", "NX", "RC", "RC F", "RX", "RX L", "RZ", "SC", "UX"],

            "Mercedes-Benz": ["AMG", "AMG GT", "AMG ONE", "A Class", "B Class", "C-Class", "CE Class", "CL", "CLC Class", "CLE", "CLK", "CLS", "CLA", "E-Class", "EQA", "EQB", "EQC", "EQE", "EQS", "EQV", "eVito", "G Class",
                "GLA", "GLB", "GLC", "GL Class", "GLE", "GLS", "Maybach GLS", "Maybach S Class", "M Class", "R Class",
                "S Class", "SEC Series", "SL", "SLC", "SLK", "SLR McLaren", "SLS", "Sprinter", "Traveliner", "Vaneo", "V Class", "Viano", "Vito", "X Class"],

            "Toyota": ["Alphard", "Aqua", "Aristo", "Auris", "Avensis", "Avensis Verso", "AYGO", "Aygo X", "BB", "Blade", "bZ4X", "Camry", "Carina E", "Celica", "Celsior", "Century", "C-HR", "Corolla",
                "Corolla Verso", "Crown", "Estima", "Estima Aeras G", "FJ Cruiser", "GR86", "Granvia", "GT86", "Harrier", "Hiace", "Highlander", "Hilux", "Ipsum", "iQ",
                "Land Cruiser", "Land Cruiser Amazon", "Land Cruiser Colorado",
                "Mark X", "MR2", "Noah", "Paseo", "Picnic", "Porte", "Previa", "Prius", "Prius+", "PROACE", "PROACE CITY Verso", "PROACE Verso", "Progres",
                "Raum", "RAV4", "Sienta", "Soarer", "Starlet", "Starlet Glanza V", "Starlet GT", "Supra", "Surf", "Tacoma", "Townace", "Tundra", "Urbancruiser", "Vellfire", "Verso", "Verso S", "Vitz", "Voxy", "Wish", "Yaris", "Yaris Cross", "Yaris Verso"],

            "Volkswagen": ["Amarok", "Arteon", "Beetle", "Bora", "Caddy", "Caddy California Maxi", "Caddy Life", "Caddy Maxi", "Caddy Maxi Life", "California", "Campervan", "Caravelle", "CC", "Corrado", "e-Golf", "Eos", "e-Transporter", "e-up!", "Fox", "Golf", "Golf Plus", "Golf SV", "Grand California",
                "ID.3", "ID.4", "ID.5", "ID.7", "ID. Buzz", "Jetta", "Karmann", "Lupo", "Multivan", "Passat", "Phaeton", "Polo", "Scirocco",
                "Sharan", "Taigo", "T-Cross", "Tiguan", "Tiguan Allspace", "Touareg", "Touran", "Transporter", "Transporter Shuttle", "Transporter Sportline", "T-Roc", "up!", "XL1"],

            "Kia": ["Carens", "Ceed", "Cerato", "EV3", "EV6", "EV9", "Magentis", "Niro", "Optima", "Picanto", "ProCeed", "Rio", "Sedona", "Sorento", "Soul", "Sportage", "Stinger", "Stonic", "Venga", "XCeed"],

            "Jaguar": ["E-PACE", "E-Type", "F-PACE", "F-Type", "I-PACE", "Mark I", "Mark II", "S-Type", "XE", "XF", "XFR-S", "XJ", "XJR", "XJR-S", "XJS", "XK", "XK120", "XK140", "XK150", "XK8", "XKR", "XKR-S", "X-Type"],

            "Infiniti": ["EX", "FX", "G", "M", "Q30", "Q50", "Q60", "Q70", "QX30", "QX56", "QX70"],

            "Hyundai": ["Accent", "Amica", "Atoz", "BAYON", "Coupe", "Genesis", "Getz", "i10", "i20", "i30", "i40", "i800", "iLoad", "IONIQ", "IONIQ 5", "IONIQ 6", "ix20", "ix35", "KONA", "Matrix", "NEXO", "Pony X2", "Santa Fe", "Sonata", "Terracan", "Trajet", "TUCSON", "Veloster"],

            "Honda": ["Accord", "Beat", "Civic", "Crossroad", "CR-V", "CR-X", "CR-Z", "e:Ny1", "Elysion", "Fit", "Freed", "FR-V", "Honda E", "HR-V", "Insight", "Integra", "Jazz", "Legend", "Mobilio", "N-Box", "NSX", "Odyssey", "Prelude", "Ridgeline", "S2000", "S660", "Shuttle", "Stepwagon", "Stream", "ZR-V"],

            "Fiat": ["124 Spider", "126", "500", "500C", "500e", "500e C", "500L", "500 Topolino", "500X", "500X Dolcevita", "600", "600e", "Barchetta", "Brava", "Bravo", "Coupe", "Doblo", "Ducato", "Fiorino", "Fullback", "Grande Punto", "Idea", "Multipla", "Panda", "Punto", "Punto Evo", "Qubo", "Scudo", "Sedici", "Seicento", "Spider", "Stilo", "Strada", "Talento", "Tipo", "Ulysse", "Uno"],

            "Dodge": ["Avenger", "Caliber", "Challenger", "Charger", "Coronet", "Journey", "Nitro", "RAM", "Viper"],

            "Ford": ["Anglia", "B-Max", "Bronco", "Capri", "C-Max", "Consul", "Cortina", "Cougar", "Custom Cab", "EcoSport", "Edge", "Escort", "E-Tourneo Custom", "E-Transit", "E-Transit Custom", "Excursion", "Explorer", "F1", "F150", "F-250", "F350", "Fiesta", "Fiesta Van", "Focus", "Focus CC", "Focus C-Max", "Fusion", "Galaxy", "Granada", "Grand C-Max", "Grand Tourneo Connect", "GT", "Ka", "Ka+", "Kuga", "Maverick", "Mondeo", "Mustang", "Mustang Mach-E", "Orion", "Prefect", "Probe", "Puma", "Ranger", "Scorpio", "Sierra", "S-Max", "Streetka", "Thunderbird", "Tourneo Connect", "Tourneo Courier", "Tourneo Custom", "Transit", "Transit Connect", "Transit Courier", "Transit Custom", "Zephyr"],

            "Nissan": ["350 Z", "370 Z", "Almera", "Altima", "Bluebird", "Cedric", "Cube", "Datsun", "Skyline", "Sunny", "Tiida", "X-Trail"]
};
   
         
        function updateMSF() {
            var msfValue = document.getElementById("msf_value")?.value || "";
            var currency = document.getElementById("currency_selector1")?.value || "";
            document.getElementById("msf").value = msfValue ? msfValue + " " + currency : "";
        }

        function updateDSF() {
            var dsfValue = document.getElementById("dsf_value")?.value || "";
            var currency = document.getElementById("currency_selector2")?.value || "";
            document.getElementById("dsf").value = dsfValue ? dsfValue + " " + currency : "";
        }
        function updateGSF(){
            var gsfValue = document.getElementById("gsf_value")?.value || "";
            var currency = document.getElementById("currency_selector4")?.value || "";
            document.getElementById("gsf").value = gsfValue ? gsfValue + " " + currency : "";
        }

                // Function to validate currency selection
        function validateCurrencySelection() {
            var msfCurrency = document.getElementById("currency_selector1").value;
            var dsfCurrency = document.getElementById("currency_selector2").value;
            var gsfCurrency = document.getElementById("currency_selector4").value;
            var chasisCurrency = document.getElementById("currency_selector5").value;

            // Check if any currency is not selected
            if (!msfCurrency || !dsfCurrency || !gsfCurrency) {
                alert("Please select a currency for all fields (MSF, DSF, and GSF).");
                return false;  // Prevent form submission
            }
            return true;  // Allow form submission
        }
                // Function to toggle the 'Other' input field based on select option
            function toggleOtherField() {
                var selectElement = document.getElementById('accident_visual');
                var otherInput = document.getElementById('other_accident_visual');
                
                if (selectElement.value === 'other') {
                    otherInput.style.display = 'block'; // Show the input field
                } else {
                    otherInput.style.display = 'none'; // Hide the input field
                }
            }

// Call the function on page load to check if 'Other' was selected previously
window.onload = function() {
    toggleOtherField();
}

        // Add event listeners only if elements exist
        document.addEventListener("DOMContentLoaded", function () {
            var msfInput = document.getElementById("msf_value");
            var msfCurrency = document.getElementById("currency_selector1");
            var dsfInput = document.getElementById("dsf_value");
            var dsfCurrency = document.getElementById("currency_selector2");
            var gsfInput = document.getElementById("gsf_value");
            var gsfCurrency = document.getElementById("currency_selector4");
            var chasisCurrency = document.getElementById("currency_selector5");
            var chasisInput = document.getElementById("chasis_value");
            

            if (msfInput) msfInput.addEventListener("input", updateMSF);
            if (msfCurrency) msfCurrency.addEventListener("change", updateMSF);
            if (dsfInput) dsfInput.addEventListener("input", updateDSF);
            if (dsfCurrency) dsfCurrency.addEventListener("change", updateDSF);
            if (gsfInput) gsfInput.addEventListener( "input", updateGSF);
            if (gsfCurrency) gsfCurrency.addEventListener("change", updateGSF);
            if (chasisCurrency) chasisCurrency.addEventListener("change", updatechasis);
            if (chasisInput) chasisInput.addEventListener( "input", updatechasis);
           
        });
        
        
        document.querySelector("form").addEventListener("submit", function(event) {
        const plateInput = document.getElementById("plate");
        const plate = plateInput.value.trim();
        const platePattern = /^[A-Z]{2} \d{3}$/; // Matches format like "VV 700"

        // Check if the plate matches the required format
        if (!platePattern.test(plate)) {
            document.getElementById("plateError").style.display = "inline"; // Show error message
            event.preventDefault(); // Prevent form submission
        } else {
            document.getElementById("plateError").style.display = "none"; // Hide error message
        }
    });

                
           function update_km_mile() {
    var km_mile_Value = document.getElementById("km_mile_value").value;
    var currency = document.getElementById("currency_selector3").value;

    // When both the numeric value and the unit are available, update the hidden input
    if (km_mile_Value && currency) {
        // Combine the numeric value with the unit (e.g., "25 KM" or "25 MILE")
        var displayValue = km_mile_Value + " " + currency;
        // Update the hidden input field (which will hold the value to submit)
        document.getElementById("km_mile").value = displayValue;
    }
}

// Add event listeners to update the hidden field when the user changes values
document.getElementById("km_mile_value").addEventListener("input", update_km_mile);
document.getElementById("currency_selector3").addEventListener("change", update_km_mile);






function updateModels() {
    const brandDropdown = document.getElementById("brand");
    const modelDropdown = document.getElementById("model");

    const selectedModel = "<?php echo isset($model) ? $model : ''; ?>"; // Get the selected model from PHP
    const selectedBrand = brandDropdown.value;

    // Clear existing options
    modelDropdown.innerHTML = '';

    // Add "Select" option
    const selectOption = document.createElement("option");
    selectOption.value = "";
    selectOption.disabled = true;
    selectOption.textContent = "Select";
    if (!selectedModel || selectedModel === "") selectOption.selected = true;
    modelDropdown.appendChild(selectOption);

    // Add models based on selected brand
    let modelFound = false;  // To track if the selected model exists in the brand list
    if (selectedBrand && carModels[selectedBrand]) {
        carModels[selectedBrand].forEach(model => {
            const option = document.createElement("option");
            option.value = model;
            option.textContent = model;

            // If this model is selected, mark it
            if (model === selectedModel) {
                option.selected = true;
                modelFound = true;  // Mark that we found the model
            }

            modelDropdown.appendChild(option);
        });
    }

    // Always add "Other" option, and select it if no valid model found
    const otherOption = document.createElement("option");
    otherOption.value = "other";
    otherOption.textContent = "Other";
    if (!modelFound && selectedModel) {
        // If selected model doesn't match any brand models, mark "Other" as selected
        otherOption.selected = true;
    }
    modelDropdown.appendChild(otherOption);

    // Show/hide custom model input
    checkOtherModel();
}

function checkOtherBrand() {
    const brandDropdown = document.getElementById("brand");
    const otherBrandInput = document.getElementById("otherBrandInput");

    if (brandDropdown.value === "other") {
        otherBrandInput.style.display = "block";
    } else {
        otherBrandInput.style.display = "none";
    }
}

function checkOtherModel() {
    const modelSelect = document.getElementById('model');
    const otherModelInput = document.getElementById('otherModelInput');

    // If "Other" is selected, show the input field
    if (modelSelect.value === 'other') {
        otherModelInput.style.display = "block";
    } else {
        otherModelInput.style.display = "none";
    }

    // If "Other" was already selected, make sure the input field is populated
    if (modelSelect.value === 'other') {
      otherModelInput.value = "<?php
        if (isset($model) && !in_array($model, $carModels[$brand] ?? [])) {
            echo htmlspecialchars($model);
        } else {
            echo '';
        }
      ?>";
    }
}




// Ensure that the correct options and inputs are shown on page load
window.onload = function() {
    updateModels();         // Load models based on selected brand
    checkOtherBrand();      // Show/hide brand input based on "Other"
    checkOtherModel();      // Show/hide model input based on "Other"
};
       // Place the Confirmation Popup Here 👇👇👇
       document.querySelector("form").onsubmit = function (event) {
        const confirmation = confirm("Are you sure you want to submit the data?");
        if (!confirmation) {
            event.preventDefault(); // Stops the submission if user clicks Cancel
        }
    };
</script>

</body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Database</title>
         <link rel="icon" href="https://kombosapp.pythonanywhere.com/static/favicon.ico" type="image/x-icon">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('static/images/img3.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;  /* Adjusted to keep content at the top */
            overflow-x: hidden; /* Prevent horizontal overflow */
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            font-size: 2rem; /* Adjusted size */
            margin: 0;
        }

        header h2 {
            font-size: 1.2rem;
            margin: 5px 0;
            color: #aaa;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85); /* Adjust opacity */
            padding: 20px;
            border-radius: 12px; /* Adjusted radius */
            width: auto; /* Allow it to adjust to content */
            min-width: 300px; /* Prevent it from becoming too small */
            max-width: 1100px; /* Keep a reasonable max width */
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            display: inline-block; /* Helps it shrink-wrap its content */
        }


        form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 10px;
            max-width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="file"] {
            width: 250px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
            margin-top:80px;
            margin-bottom :0px;
            height:50px;
            
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
            padding: 10px 20px;
            font-size: 1em;
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
            padding: 10px;
            width: 100%;
            margin-top: auto;  /* Ensure footer stays at the bottom */
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr; /* Stack form fields on smaller screens */
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
</html>