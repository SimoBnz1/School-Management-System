<?php
require '../scripts/connection.php';
session_start();
function checkCours($conn, $title)
{
    try {
        $sql = "SELECT * FROM courses WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["title" => $title]);
        $cours = $stmt->fetch();
        return $cours ? true : false;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addCours($conn, $title, $description,$total_hour,$prof)
{
    try {
        $sql = "INSERT INTO courses (title,description,total_hours,user_id)
        VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$title,$description,$total_hour,$prof]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD']== 'POST') {

    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['discription']);
   
    $total_hour=htmlspecialchars($_POST['total_hour']);
    $prof=htmlspecialchars($_POST['prof']);

    if (checkCours($conn, $title)) {
        header("Location: ../admin/courses/courses.php");
        exit();
    }
    if (!empty($title) && !empty($description) && !empty($total_hour) && !empty($prof)) {
    addCours($conn, $title, $description,$total_hour,$prof);
    header("Location: ../admin/courses/courses.php");
    } else {
        header("Location:../admin/courses/form_Add_Cours.php?err=champVid");
    }
}
