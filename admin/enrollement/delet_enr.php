<?php
require '../../scripts/connection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM enrollments WHERE id=?");
$stmt->execute([$id]);

header("Location: enrollments.php");