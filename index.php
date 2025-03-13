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
        $chasis = $row['chasis'];

        $brand = $row['brand'];
        $year = $row['year'];
        $model = $row['model'];
        $km_mile = $row['km_mile'];
        $accident_visual = $row['accident_visual'];
        $accident_tramer = $row['accident_tramer'];
        $msf = $row['msf'];
        $dsf = $row['dsf'];
        $gsf=$row['gsf'];
        $package = $row['package'];
        $color = $row['color'];
        $engine = $row['engine'];
        $gear = $row['gear'];
        $fuel = $row['fuel'];
        $expense_detail = $row['expense_detail'];
        $current_expense = $row['current_total_expense'];
        $image = $row['image']; // Get the image filename if exists
        $image2 = $row['image2']; // Get the image filename if exists
        $image3 = $row['image3']; // Get the image filename if exists
        $image4 = $row['image4']; // Get the image filename if exists
    }
} else {
    // Default values for a new car entry
    $customer_name = $plate =$chasis= $brand = $year = $model = $km_mile = $accident_visual = $accident_tramer = $msf = $dsf= $gsf = $package = $color = $engine = $gear = $fuel = $expense_detail = $current_expense = $image = $image2= $image3=$image4='';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $plate = $_POST['plate'];
    $chasis = $_POST['chasis'];
    $brand = $_POST['brand'];
    $year = $_POST['year'];
    $model = $_POST['model'];
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

        // Process the km_mile field before inserting into the database
        if (!empty($km_mile)) {
            // Remove any non-numeric characters except for the decimal point
            $km_mile_numeric = preg_replace('/[^0-9.]/', '', $km_mile);
            $km_mile = floatval($km_mile_numeric); // Convert it to a float
        } else {
            $km_mile = NULL; // If the km_mile is empty, set it to NULL or 0 based on your preference
        }

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


     // Handle file upload for second file
     if (isset($_FILES['image2']) && $_FILES['image2']['error'] == 0) {
        $image2 = $_FILES['image2']['name'];
        $image_tmp2 = $_FILES['image2']['tmp_name'];
        $image_folder2 = 'uploads/' . $image2;
        move_uploaded_file($image_tmp2, $image_folder2);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image2 = $row['image2'];
    }
     // Handle file upload for second file
     if (isset($_FILES['image3']) && $_FILES['image3']['error'] == 0) {
        $image3 = $_FILES['image3']['name'];
        $image_tmp3 = $_FILES['image3']['tmp_name'];
        $image_folder3 = 'uploads/' . $image3;
        move_uploaded_file($image_tmp3, $image_folder3);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image3 = $row['image3'];
    }

     // Handle file upload for second file
     if (isset($_FILES['image4']) && $_FILES['image4']['error'] == 0) {
        $image4 = $_FILES['image4']['name'];
        $image_tmp4 = $_FILES['image4']['tmp_name'];
        $image_folder4 = 'uploads/' . $image4;
        move_uploaded_file($image_tmp4, $image_folder4);
    } elseif (isset($_GET['edit_id'])) {
        // Keep the existing image if it's being updated and no new image is uploaded
        $image4 = $row['image4'];
    }

    if (isset($_GET['edit_id'])) {
        // Update the car details
        $sql = "UPDATE cars SET customer_name = '$customer_name', plate = '$plate', chasis = '$chasis', brand = '$brand', year = '$year', model = '$model', km_mile = '$km_mile', accident_visual = '$accident_visual', accident_tramer = '$accident_tramer', msf = '$msf', dsf = '$dsf', gsf='$gsf', package = '$package', color = '$color', engine = '$engine', gear = '$gear', fuel = '$fuel', expense_detail = '$expense_detail', current_total_expense = '$current_expense', image = '$image' , image2 = '$image2', image3 = '$image3', image4 = '$image4' WHERE id = '$edit_id'";
        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate,$chasis, $brand,$year, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $gsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense,$image, $image2,$image3,$image4);
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Insert new car data
        $sql = "INSERT INTO cars (customer_name, plate, chasis, brand, year, model, km_mile, accident_visual, accident_tramer, msf, dsf, gsf, package, color, engine, gear, fuel, expense_detail, current_total_expense,image,image2,image3,image4 ) 
        VALUES ('$customer_name', '$plate', '$chasis', '$brand','$year' ,'$model', '$km_mile', '$accident_visual', '$accident_tramer', '$msf', '$dsf','$gsf', '$package', '$color', '$engine', '$gear', '$fuel', '$expense_detail', '$current_expense', '$image','$image2','$image3','$image4')";
        if ($conn->query($sql) === TRUE) {
            // Clear form after successful submission
            unset($customer_name, $plate,$chasis, $brand,$year, $model, $km_mile, $accident_visual, $accident_tramer, $msf, $dsf, $gsf, $package, $color, $engine, $gear, $fuel, $expense_detail, $current_expense);
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
        <?php 
        $fields = [
            'customer_name' => 'Müşteri Adi | Customer Name', 'plate' => 'Plaka | Plate', 'chasis'=>'Şasi Numarasi | Chasis Number', 'accident_tramer' => 'Kaza Tramer | Accident Tramer', 
             'package' => 'Paket | Package', 'color' => 'Renk | Color', 
            'engine' => 'Motor | Engine', 'expense_detail' => 'Masraf Detayi | Expense Detail', 'current_expense' => 'Aktuel Masraf  Toplami|Current Total Expense'
        ];
        

foreach ($fields as $id => $label) {
    if ($id == 'chasis') {
        echo "
        <div class='form-group'>
            <label for='$id'>$label:</label>
            <input type='text' id='$id' name='$id' value='" . ($$id ?? '') . "' minlength='17' maxlength='17' required placeholder='Enter 17-digit Chasis Number'/>
        </div>
        ";
    } else {
        echo "
        <div class='form-group'>
            <label for='$id'>$label:</label>
            <input type='text' id='$id' name='$id' value='" . ($$id ?? '') . "' required />
        </div>
        ";
    }
}

        ?>

        <div class="form-group">
            <label for="gear">Vites | Gear:</label>
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
                        <option value="EUR" <?php echo ($msf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                        <option value="TL" <?php echo ($msf_currency == 'TL') ? 'selected' : ''; ?>>₺ TL</option>
                    </select>
                    <input type="hidden" id="msf" name="msf" value="<?php echo htmlspecialchars($msf); ?>" />
                </div>
            </div>

        <!-- Dunusunlar Satis Fiyat (DSF) -->
        <div class="form-group">
            <label for="dsf">Düsünülen Satis Fiyati (DSF):</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="number" id="dsf_value" step="0.01" placeholder="Enter price" value="<?php echo htmlspecialchars($dsf_value); ?>" required />
                <select id="currency_selector2" onchange="updateDSF()" required>
                    <option value="" disabled <?php echo empty($dsf_currency) ? 'selected' : ''; ?>>Select</option>
                    <option value="EUR" <?php echo ($dsf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                    <option value="TL" <?php echo ($dsf_currency == 'TL') ? 'selected' : ''; ?>>₺ TL</option>
                </select>
                <input type="hidden" id="dsf" name="dsf" value="<?php echo htmlspecialchars($dsf); ?>" />
            </div>
        </div>
        <!-- Gerçekleşen Satis Fiyati (GSF) -->
        <div  class ="form-group">
            <label for ="gsf"> Gerçekleşen Satiş Fiyati (GSF):</label>
            <div style = "display: flex; gap: 10px; align-items: center;">
                <input type= "number"  id="gsf_value" step="0.01" placeholder= "Enter price" value="<?php echo htmlspecialchars($gsf_value); ?>" required />
                <select id= "currency_selector4" onchange= "updateGSF()" required>
                    <option value="" disabled <?php echo empty ($gsf_currency) ? 'selected' : ''; ?>>Select</option>
                    <option value="EUR" <?php echo($gsf_currency == 'EUR') ? 'selected' : ''; ?>>€ EUR</option>
                    <option value="TL" <?php echo ($gsf_currency == 'TL') ? 'selected': ''; ?>>₺ TL</option>
                </select>
                <input type="hidden" id="gsf" name="gsf" value="<?php echo htmlspecialchars($gsf); ?>" />
                </div>
            </div>
        
        <div class="form-group">
    <label for="km_mile">Mil/KM | Mile/KM:</label>
    <div style="display: flex; gap: 10px; align-items: center;">
                <!-- Input for km/mile value -->
                <input type="number" id="km_mile_value" name="km_mile_value" step="0.01" placeholder="Enter value" value="<?php echo htmlspecialchars($km_mile); ?>" required />

                <!-- Select for unit (KM or MILE) -->
                <select id="currency_selector3" name="currency_selector3" onchange="update_km_mile()" required>
                    <option value="" disabled <?php echo empty($km_mile) ? 'selected' : ''; ?>>Select</option>
                    <option value="KM" <?php echo ($km_mile && strpos($km_mile, 'KM') !== false) ? 'selected' : ''; ?>>KM</option>
                    <option value="MILE" <?php echo ($km_mile && strpos($km_mile, 'MILE') !== false) ? 'selected' : ''; ?>>MILE</option>
                </select>

                <!-- Hidden input for km_mile value to be submitted -->
                <input type="hidden" id="km_mile" name="km_mile" value="<?php echo htmlspecialchars($km_mile); ?>" />
            </div>
        </div>
        



        <div class="form-group">
            <label for="year">Üretim Yili | Year of Manufacture:</label>
            <select id="year" name="year"  required>
                <option value="" disabled <?php echo empty($year) ? 'selected' : ''; ?>>Select</option>
                <?php
                    $yearOptions = ["2025", "2024", "2023", "2022", "2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014", 
                    "2013", "2012", "2011", "2010", "2009", "2008", "2007", "2006", "2005", "2004", "2003", "2002", 
                    "2001", "2000", "1999", "1998", "1997", "1996", "1995", "1994", "1993", "1992", "1991", "1990", "1989"
                    ];
                    foreach ($yearOptions as $yearOption) {
                    $selected = ($year == $yearOption) ? 'selected' : '';
                    echo "<option value='$yearOption' $selected>$yearOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="accident_visual">Kaza Görsel | Accident Visual:</label>
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
            <label for="fuel">Yakit | Fuel:</label>
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
            <label for="brand">Marka | Brand:</label>
            <select id="brand" name="brand" onchange="updateModels()">
                <option value="" disabled <?php echo empty($brand) ? 'selected' : ''; ?>>Select</option>
                <?php
                   $brands = ["Acura", "Alfa Romeo", "Audi", "BMW", "Chevrolet", "Chrysler", "Citroen", "Dodge", "Fiat", "Ford", "Honda", "Hyundai", "Infiniti", "Jaguar", "Kia", "Lexus","Maybach", "Mazda", "Mercedes-Benz", "Nissan", "Peugeot", "Renault", "Subaru", "Toyota", "Volkswagen"];
                foreach ($brands as $brandOption) {
                    $selected = ($brand == $brandOption) ? 'selected' : '';
                    echo "<option value='$brandOption' $selected>$brandOption</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="model">Modeli | Model:</label>
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

<label for="image2">Car Image2:</label>
    <input type="file" id="image2" name="image2" <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> />
    <?php
    if (!empty($image2) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image2: <img src='uploads/$image2' alt='Car Image2' width='100' /></p>";
    }
    ?>

<label for="image3">Car Image3:</label>
    <input type="file" id="image3" name="image3" <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> />
    <?php
    if (!empty($image3) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image3: <img src='uploads/$image3' alt='Car Image3' width='100' /></p>";
    }
    ?>
    <label for="image4">Car Image4:</label>
    <input type="file" id="image4" name="image4" <?php echo isset($_GET['edit_id']) ? '' : 'required'; ?> />
    <?php
    if (!empty($image4) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_id'])) {
        echo "<p>Current Image4: <img src='uploads/$image4' alt='Car Image4' width='100' /></p>";
    }
    ?>
</div>

        
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

            // Check if any currency is not selected
            if (!msfCurrency || !dsfCurrency || !gsfCurrency) {
                alert("Please select a currency for all fields (MSF, DSF, and GSF).");
                return false;  // Prevent form submission
            }
            return true;  // Allow form submission
        }

        // Add event listeners only if elements exist
        document.addEventListener("DOMContentLoaded", function () {
            var msfInput = document.getElementById("msf_value");
            var msfCurrency = document.getElementById("currency_selector1");
            var dsfInput = document.getElementById("dsf_value");
            var dsfCurrency = document.getElementById("currency_selector2");
            var gsfInput = document.getElementById("gsf_value");
            var gsfCurrency = document.getElementById("currency_selector4");

            if (msfInput) msfInput.addEventListener("input", updateMSF);
            if (msfCurrency) msfCurrency.addEventListener("change", updateMSF);
            if (dsfInput) dsfInput.addEventListener("input", updateDSF);
            if (dsfCurrency) dsfCurrency.addEventListener("change", updateDSF);
            if (gsfInput) gsfInput.addEventListener( "input", updateGSF);
            if (gsfCurrency) gsfCurrency.addEventListener("change", updateGSF);
        });

        function update_km_mile() {
            var km_mile_Value = document.getElementById("km_mile_value").value;
            var currency = document.getElementById("currency_selector3").value;
            document.getElementById("km_mile").value = km_mile_Value ? km_mile_Value + " " + currency : "";
        }

        document.getElementById("km_mile_value").addEventListener("input", update_km_mile);
        document.getElementById("currency_selector3").addEventListener("change", update_km_mile);





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

</body>

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