<?php
require '../../scripts/connection.php';

$id = $_POST['id'];
$name = $_POST['firstname'];
$email = $_POST['email'];
$role = $_POST['role_id'];

$stmt = $conn->prepare("UPDATE users SET firstname=?, email=?, role_id=? WHERE id=?");
$stmt->execute([$name, $email, $role, $id]);
 header("Location: users.php");