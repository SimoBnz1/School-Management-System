login_logique
<?php
session_start();
require '../scripts/connection.php';
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
