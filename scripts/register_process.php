<?php
require '../scripts/connection.php';
session_start();
function checkemail($conn, $email)
{
    try {
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();
        return $user ? true : false;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
