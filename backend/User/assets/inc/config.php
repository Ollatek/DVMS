<?php
$dbuser="root";
$dbpass="Olakunle";
$host="localhost";
$db="hmisphp";
$mysqli=new mysqli($host,$dbuser, $dbpass, $db);

$conn = new mysqli("localhost", "root", "Olakunle", "hmisphp"); // Replace with your actual database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
