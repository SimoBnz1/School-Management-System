<?php
require '../../scripts/connection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
$stmt->execute([$id]);

header("Location: courses.php");