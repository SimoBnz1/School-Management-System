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

function addUser($conn, $firstname, $lastname, $email, $password, $role)
{
    echo "role";
    try {
        $sql = "INSERT INTO users (firstname,lastname,email,password,role_id)
        VALUES (:firstname,:lastname,:email,:password,:role_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'role_id' =>  $role
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD']== 'POST') {

    $firstname = htmlspecialchars($_POST['firstName']);
    $lastname = htmlspecialchars($_POST['lastName']);
    $email = htmlspecialchars(trim($_POST['email']));
    $role = htmlspecialchars(trim($_POST['role']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=fomat unvalidate");
        exit();
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confpassword = $_POST['confirmPassword'];

    if ($_POST['password'] !== $confpassword) {
        header("Location:../public/register.php?err=passM");
        exit();
    }

    if (checkemail($conn, $email)) {
        header("Location:../public/login.php?err=exist");
        exit();
    }
    if (!empty($firstname) && !empty($lastname)) {
    addUser($conn, $firstname, $lastname, $email, $password, $role);
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['role']=$user;
    header("Location: ../admin/users.php");
    } else {
        header("Location:../public/register.php?err=champVid");
    }
}
