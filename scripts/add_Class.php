<?php
require '../scripts/connection.php';
session_start();
function checkClass($conn, $name)
{
    try {
        $sql = "SELECT * FROM classes WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["name" => $name]);
        $class = $stmt->fetch();
        return $class ? true : false;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addUser($conn, $name, $classroom_number)
{
    try {
        $sql = "INSERT INTO classes (name,classroom_number)
        VALUES (:name,:classroom_number)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'classroom_number' => $classroom_number
            
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD']== 'POST') {

    $name = htmlspecialchars($_POST['name']);
    $classroom_number = htmlspecialchars($_POST['classroom']);
   

    if (checkClass($conn, $name)) {
        header("Location: ../admin/classes/classes.php");
        exit();
    }
    if (!empty($name) && !empty($classroom_number)) {
    addUser($conn, $name, $classroom_number);
  
    header("Location: ../admin/classes/classes.php");
    } else {
        header("Location:../admin/classes/form_Add_Classes.php?err=champVid");
    }
}
