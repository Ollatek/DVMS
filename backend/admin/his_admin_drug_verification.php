<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drug Verification</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Include your CSS -->
</head>
<body>
    <h2>Drug Verification</h2>
    <form method="POST" action="his_doc_drug_verification.php">
        <label for="rfid">Enter RFID Tag / Barcode:</label>
        <input type="text" id="rfid" name="rfid" required placeholder="Scan RFID tag or enter barcode">
        <button type="submit">Verify</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rfid = $_POST['rfid'];
        // Placeholder: Query to check RFID/barcode in the database
        echo "<p>Verifying tag: <strong>$rfid</strong></p>";
        // Add database connection and verification logic here
// Database connection
$conn = new mysqli("localhost", "username", "password", "hmisphp_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $conn->real_escape_string($_POST['rfid']);

    // Query to check if the RFID/barcode exists
    $query = "SELECT * FROM drugs WHERE rfid_tag = '$rfid' OR barcode = '$rfid'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $drug = $result->fetch_assoc();
        echo "<p>Drug Found: <strong>" . $drug['name'] . "</strong></p>";
    } else {
        echo "<p style='color:red;'>No matching drug found!</p>";
    }
}

$conn->close();

    }
    ?>
	
	
</body>
</html>
