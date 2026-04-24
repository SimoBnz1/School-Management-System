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

function addUser($conn, $firstname, $lastname, $email, $password)
{
    try {
        $sql = "INSERT INTO users (firstname,lastname,email,password,role_id)
        VALUES (:firstname,:lastname,:email,:password,:role_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'role_id' => 3
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}