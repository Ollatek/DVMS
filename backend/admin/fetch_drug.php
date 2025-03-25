<?php
session_start();
include('assets/inc/config.php');  // Database connection

header('Content-Type: application/json');

if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo json_encode(['error' => 'No query provided']);
    exit;
}

$query = $_GET['query'];
$response = ['found' => false];

try {
    $sql = "SELECT * FROM drugphp WHERE name = ? OR nafdac_no = ? OR ATC_code = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(['error' => 'Database error: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("sss", $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response = [
            'found' => true,
            'name' => $row['name'],
            'generic_name' => $row['generic_name'],
			'manufacturer' => $row['manufacturer'],
            'nafdac_no' => $row['nafdac_no'],
            'description' => $row['description']
        ];
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Exception: ' . $e->getMessage()]);
}
?>
