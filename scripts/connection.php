<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=edusync", "root", "");
} catch (PDOException $e) {
    echo " Connection failed: " . $e->getMessage();
}
?>