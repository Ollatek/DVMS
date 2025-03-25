<?php
header("Content-Type: application/json"); // Ensure JSON output

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$username = "root"; // Change if different
$password = "Olakunle"; // Change if different
$database = "hmisphp";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (empty($search)) {
    die(json_encode(["error" => "Search query is empty"]));
}

// Prepare query
$sql = "SELECT * FROM drugphp WHERE name LIKE ? OR nafdac_reg_no = ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $search);

if (!$stmt->execute()) {
    die(json_encode(["error" => "Query execution failed: " . $stmt->error]));
}

$result = $stmt->get_result();
$drugs = [];

while ($row = $result->fetch_assoc()) {
    $drugs[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($drugs);
?>
