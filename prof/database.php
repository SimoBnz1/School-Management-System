<?php
$host = "localhost";
$dbname = "edusync";
$dbUID = "root";
$DBpassword = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUID, $DBpassword);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

