<?php
session_start();
require_once("../School-Management-System/scripts/connection.php");

// 🔐 Vérification session + role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'prof') {
    header("Location: ../School-Management-System/public/login.php");
    exit();
}

// 📌 جلب cours ديال prof
$stmt = $pdo->prepare("SELECT * FROM courses WHERE prof_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prof Dashboard</title>
</head>
<body>

<h2>Bienvenue Prof 👨‍🏫</h2>

<h3>Mes cours</h3>

<?php if ($courses): ?>
    <ul>
        <?php foreach ($courses as $course): ?>
            <li>
                <strong><?php echo $course['name']; ?></strong>

                <!-- Voir étudiants -->
                <a href="course_students.php?id=<?php echo $course['id']; ?>">
                    Voir étudiants
                </a>

                <!-- Voir classe -->
                <a href="class_details.php?id=<?php echo $course['class_id']; ?>">
                    Voir classe
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun cours trouvé.</p>
<?php endif; ?>

<br>

<!-- Logout -->
<a href="../logout.php">Se déconnecter</a>

</body>
</html>