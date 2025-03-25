
<?php
include ('assets/inc/his_admin_dvm.php');

$id = intval($_GET['id']);
$error = "";

// Fetch drug details
$sql = "SELECT * FROM drugs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$drug = $result->fetch_assoc();

if (!$drug) {
    die("Drug not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = $conn->real_escape_string(trim($_POST['name']));
    $category = $conn->real_escape_string(trim($_POST['category']));
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;

    // Validate inputs
    if (empty($name) || $quantity <= 0 || $price <= 0) {
        $error = "Please fill out all required fields correctly.";
    } else {
        // Update the drug details in the database
        $update_sql = "UPDATE drugs SET name = ?, category = ?, quantity = ?, price = ?, expiry_date = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssidsi", $name, $category, $quantity, $price, $expiry_date, $id);

        if ($update_stmt->execute()) {
            header("Location: his_admin_dvm.php?success=1");
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Drug</title>
</head>
<body>
    <h1>Edit Drug</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="name">Drug Name: <span style="color: red;">*</span></label>
        <input type="text" name="name" value="<?= htmlspecialchars($drug['name']); ?>" required><br>

        <label for="category">Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($drug['category']); ?>"><br>

        <label for="quantity">Quantity: <span style="color: red;">*</span></label>
        <input type="number" name="quantity" value="<?= htmlspecialchars($drug['quantity']); ?>" min="1" required><br>

        <label for="price">Price: <span style="color: red;">*</span></label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($drug['price']); ?>" min="0.01" required><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="date" name="expiry_date" value="<?= htmlspecialchars($drug['expiry_date']); ?>"><br>

        <button type="submit">Update Drug</button>
    </form>
    <p><a href="index.php">Back to Inventory</a></p>
</body>
</html>