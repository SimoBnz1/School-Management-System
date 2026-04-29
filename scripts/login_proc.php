login_logique
<?php
session_start();
require '../scripts/connection.php';
include  '../public/dashboard.php';
function getPassword($email, $password, $conn)
{
    try {
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            };
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
};

if (isset($_POST['login'])) {
    $email=$_POST['email'];
    $password=$_POST['password'];

   if (getPassword($email,$password,$conn)) {
    $user=getPassword($email,$password,$conn);
    $_SESSION['user_id']=$user['id'];
    $_SESSION['firstname']=$user['firstname'];
    $_SESSION['lastname']=$user['lastname'];
    header("Location: ../student/dashboard.php");
    exit();
   }else{
    header("Location:../public/login.php?err=wrong");
    exit();
   };
};