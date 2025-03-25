<?php
include 'includes/his_admin_dvm.php';

$name = $category = $quantity = $price = $expiry_date = "";
$error = "";

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
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO drugs (name, category, quantity, price, expiry_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $name, $category, $quantity, $price, $expiry_date);

        if ($stmt->execute()) {
            header("Location: his_admin_dvm.php?success=1");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Drug</title>
</head>
<body>
    <h1>Add New Drug</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="name">Drug Name: <span style="color: red;">*</span></label>
        <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required><br>

        <label for="category">Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($category); ?>"><br>

        <label for="quantity">Quantity: <span style="color: red;">*</span></label>
        <input type="number" name="quantity" value="<?= htmlspecialchars($quantity); ?>" min="1" required><br>

        <label for="price">Price: <span style="color: red;">*</span></label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price); ?>" min="0.01" required><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="date" name="expiry_date" value="<?= htmlspecialchars($expiry_date); ?>"><br>

        <button type="submit">Add Drug</button>
    </form>
    <p><a href="index.php">Back to Inventory</a></p>
</body>
</html>