<?php
session_start();
include ('assets/inc/drugphp.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = trim($_POST["name"]);
    $generic_name = trim($_POST["generic_name"]);
    $nafdac_reg_no = trim($_POST["nafdac_reg_no"]);
    $category = trim($_POST["category"]);
    $type = trim($_POST["type"]);
    $composition = trim($_POST["composition"]);
    $manufacturer = trim($_POST["manufacturer"]);
    $country = trim($_POST["country"]);
	$ATC_code = $conn->real_escape_string(trim($_POST["ATC_code"]));
	$description = trim($_POST["description"]);
    $expiry_date = !empty($_POST["expiry_date"]) ? date("Y-m", strtotime($_POST["expiry_date"])) : NULL;
    $manufacture_date = !empty($_POST["manufacture_date"]) ? $_POST["manufacture_date"] : NULL;
    $storage_condition = trim($_POST["storage_condition"]);

    // Check for required fields
    if (empty($name) || empty($generic_name) || empty($nafdac_reg_no) || empty($category) || empty($type) || empty($composition) || empty($manufacturer) || empty($country) || empty($ATC_code) || empty($description) || empty($storage_condition)) {
        $error = "All required fields must be filled!";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO drugphp (name, generic_name, nafdac_reg_no, category, type, composition, manufacturer, country, ATC_code, description, expiry_date, manufacture_date, storage_condition) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssssssss", $name, $generic_name, $nafdac_reg_no, $category, $type, $composition, $manufacturer, $country, $ATC_code, $description, $expiry_date, $manufacture_date, $storage_condition);

            if ($stmt->execute()) {
                // Redirect or success message
                header("Location: his_admin_pharm_inventory.php?success=Drug added successfully");
                exit();
            } else {
                $error = "Error adding drug. Please try again.";
            }

            $stmt->close();
        } else {
            $error = "Database error: Unable to prepare statement.";
        }
    }

    $conn->close();
}
if (!empty($_POST["expiry_date"])) {
    $expiry_date = date("Y-m", strtotime($_POST["expiry_date"])); // Stores as YYYY-MM
} else {
    $expiry_date = NULL;
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Drug</title>
    <link rel="stylesheet" href="assets/css/drug.css">
</head>
<body>
    <div class="container">
        <div class="left-side"></div>
        <div class="right-side">
            <h1>Add New Drug</h1>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="his_admin_dvm.php" method="POST">
                <div class="form-group">
                    <label for="name">Drug Name: <span style="color: red;">*</span></label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label for="generic_name">Generic Name: <span style="color: red;">*</span></label>
                    <input type="text" name="generic_name" required>
                </div>

                <div class="form-group">
                    <label for="nafdac_reg_no">NAFDAC Reg. No: <span style="color: red;">*</span></label>
                    <input type="text" name="nafdac_reg_no" required>
                </div>

                <div class="form-group">
                    <label for="category">Category: <span style="color: red;">*</span></label>
                    <input type="text" name="category" required>
                </div>

                <div class="form-group">
                    <label for="type">Type: <span style="color: red;">*</span></label>
                    <input type="text" name="type" required>
                </div>

                <div class="form-group">
                    <label for="composition">Composition: <span style="color: red;">*</span></label>
                    <input type="text" name="composition" required>
                </div>

                <div class="form-group">
                    <label for="manufacturer">Manufacturer: <span style="color: red;">*</span></label>
                    <input type="text" name="manufacturer" required>
                </div>

                <div class="form-group">
                    <label for="country">Country of Origin: <span style="color: red;">*</span></label>
                    <input type="text" name="country" required>
                </div>

                <div class="form-group">
                    <label for="ATC_code">ATC Code: <span style="color: red;">*</span></label>
                    <input type="text" name="ATC_code" required>
                </div>
				
                <div class="form-group">
                    <label for="description">Decription: <span style="color: red;">*</span></label>
                    <input type="text" name="description" required>
                </div>

                <div class="form-group">
                    <label for="expiry_date">Expiry Date:</label>
                    <input type="month" name="expiry_date">
                </div>

                <div class="form-group">
                    <label for="manufacture_date">Manufacture Date:</label>
                    <input type="month" name="manufacture_date">
                </div>

                <div class="form-group">
                    <label for="storage_condition">Storage Condition: <span style="color: red;">*</span></label>
                    <input type="text" name="storage_condition" required>
                </div>

                <button type="submit">Add Drug</button>
            </form>
            <p><a href="his_admin_pharm_inventory.php">Back to Inventory</a></p>
        </div>
    </div>
</body>
</html>
