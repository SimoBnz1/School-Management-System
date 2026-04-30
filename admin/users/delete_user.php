<?php
require '../../scripts/connection.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$id]);

header("Location: users.php");