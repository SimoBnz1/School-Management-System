<?php
require '../../scripts/connection.php';

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;
$classroom_number = $_POST['classroom'] ?? null;

if ($id && $name && $classroom_number) {
    $stmt = $conn->prepare("UPDATE classes SET name=?, classroom_number=? WHERE id=?");
    $stmt->execute([$name, $classroom_number, $id]);

    header("Location: classes.php");
    exit();
} else {
    echo "Données manquantes";
}
?>