<?php
require '../../scripts/connection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM classes WHERE id=?");
$stmt->execute([$id]);

header("Location: classes.php");