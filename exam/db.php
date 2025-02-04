<?php
// db.php
$host = "localhost";
$dbname = "user_management"; // Ensure this matches your actual DB name
$username = "root"; // Change if needed
$password = ""; // Change if your MySQL has a password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}


?>