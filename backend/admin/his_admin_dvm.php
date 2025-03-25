<?php
session_start();
include ('assets/inc/drugphp.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Form not submitted via POST.");
}

	
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
    $expiry_date = !empty($_POST["expiry_date"]) ? $_POST["expiry_date"] : NULL;
    $manufacture_date = !empty($_POST["manufacture_date"]) ? $_POST["manufacture_date"] : NULL;
    $storage_condition = trim($_POST["storage_condition"]);

    // Check for required fields
    if (empty($name) || empty($generic_name) || empty($nafdac_reg_no) || empty($category) || empty($type) || empty($composition) || empty($manufacturer) || empty($country) || empty($ATC_code) || empty($storage_condition)) {
        $error = "All required fields must be filled!";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO drugphp (name, generic_name, nafdac_reg_no, category, type, composition, manufacturer, country, ATC_code, expiry_date, manufacture_date, storage_condition) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssssssss", $name, $generic_name, $nafdac_reg_no, $category, $type, $composition, $manufacturer, $country, $ATC_code, $expiry_date, $manufacture_date, $storage_condition);

            if ($stmt->execute()) {
                // Redirect or success message
                header("Location: his_admin_pharm_inventory.php?success=Drug added successfully");
                exit();
            } else {
                $error = "Error adding drug. Please try again.";
            }

           
        } else {
            $error = "Database error: Unable to prepare statement.";
        }
    }

    $conn->close();
}
?>
	?>
