<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (!isset($_GET['query'])) {
    echo json_encode(["error" => "Missing query parameter"]);
    exit;
}

$query = urlencode($_GET['query']);
$api_url = "https://api.druglist.ng/search?query=" . $query;

$response = file_get_contents($api_url);
if ($response === FALSE) {
    echo json_encode(["error" => "Failed to fetch data"]);
} else {
    echo $response;
}
?>
