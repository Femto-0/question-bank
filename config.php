<?php
// configuration to connect to the database

$host = "localhost"; //  database host
$dbname = "question_bank"; // Database name
$username = "root"; // Database username
$password = "root@1234"; // Database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

