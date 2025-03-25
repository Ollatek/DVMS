
<?php
include 'includes/his_admin_dvm.php';

// Fetch drugs from the database
$sql = "SELECT * FROM drugphp";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Drug Inventory</title>
</head>
<body>
    <h1>Drug Inventory Management</h1>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Action completed successfully!</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">An error occurred. Please try again.</p>
    <?php endif; ?>

    <!-- Add New Drug -->
    <a href="add_drug.php">Add New Drug</a>

    <!-- Drug Inventory Table -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= htmlspecialchars($row['quantity']); ?></td>
                        <td><?= htmlspecialchars(number_format($row['price'], 2)); ?></td>
                        <td><?= htmlspecialchars($row['expiry_date']); ?></td>
                        <td>
                            <a href="edit_drug.php?id=<;?= $row['id']; ?>">Edit</a>
                            <a href="delete_drug.php?id=<;?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this drug?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No drugs found in the inventory.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>