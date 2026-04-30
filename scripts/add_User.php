<?php
require '../scripts/connection.php';
session_start();


function checkemail($conn, $email)
{
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);

    return $stmt->fetch() ? true : false;
}


function addUser($conn, $firstname, $lastname, $email, $password, $role)
{
    $sql = "INSERT INTO users (firstname, lastname, email, password, role_id)
            VALUES (:firstname, :lastname, :email, :password, :role_id)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'email'     => $email,
        'password'  => $password,
        'role_id'   => $role
    ]);
}


function addStudent($conn, $dateofbirth, $student_number, $id_classe, $user_id)
{
    $sql = "INSERT INTO students (dateofbirth, student_number, class_id, user_id)
            VALUES (:dateofbirth, :student_number, :class_id, :user_id)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':dateofbirth'    => $dateofbirth,
        ':student_number' => $student_number,
        ':class_id'       => $id_classe,
        ':user_id'        => $user_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstname = htmlspecialchars($_POST['firstName'] ?? '');
    $lastname  = htmlspecialchars($_POST['lastName'] ?? '');
    $email     = htmlspecialchars(trim($_POST['email'] ?? ''));
    $role      = $_POST['role'] ?? '';

    $password      = $_POST['password'] ?? '';
    $confpassword  = $_POST['confirmPassword'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../public/register.php?err=emailInvalid");
        exit();
    }

    if ($password !== $confpassword) {
        header("Location: ../public/register.php?err=passMismatch");
        exit();
    }

    if (checkemail($conn, $email)) {
        header("Location: ../public/register.php?err=emailExist");
        exit();
    }

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($role)) {
        header("Location: ../admin/users/form_Add_User.php?err=champVid");
        exit();
    }
    
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conn->beginTransaction();

      
        addUser($conn, $firstname, $lastname, $email, $password, $role);

        $user_id = $conn->lastInsertId();

        if ($role == 3) {

            $dateofbirth    = $_POST['dateofbirth'] ?? '';
            $student_number = $_POST['student_number'] ?? '';
            $id_classe      = $_POST['id_classe'] ?? '';

            if (empty($dateofbirth) || empty($student_number) || empty($id_classe)) {
                throw new Exception("Student data missing");
            }

            addStudent($conn, $dateofbirth, $student_number, $id_classe, $user_id);
        }

      
        $conn->commit();

        header("Location: ../admin/users/users.php");
        exit();

    } catch (Exception $e) {

      
        $conn->rollBack();

        echo "Error: " . $e->getMessage();
    }
}