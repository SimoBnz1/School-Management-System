<?php
session_start();
require '../scripts/connection.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'étudiant
$sql = "SELECT u.firstname, u.lastname, s.student_number, c.name AS class_name, c.classroom_number
        FROM users u
        JOIN students s ON u.id = s.user_id
        JOIN classes c ON s.class_id = c.id
        WHERE u.id = $user_id";
$resultat = $conn->query($sql);
$student_info = $resultat->fetch(PDO::FETCH_OBJ);

// Récupérer les cours
$sql2 = "SELECT c.title, c.description, c.total_hours, e.status, e.enrolled_at,
                p.firstname AS prof_firstname, p.lastname AS prof_lastname
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN students s ON e.student_id = s.id
        JOIN users p ON c.user_id = p.id
        WHERE s.user_id = $user_id";
$resultat2 = $conn->query($sql2);
$mes_cours = $resultat2->fetchAll(PDO::FETCH_OBJ);

// Calculer les statistiques simples
$total_courses = count($mes_cours);
$total_hours = 0;
foreach($mes_cours as $cours) {
    $total_hours += $cours->total_hours;
}

// Récupérer le nombre de camarades
$sql3 = "SELECT COUNT(*) - 1 AS cnt
        FROM students s1
        JOIN students s2 ON s1.class_id = s2.class_id
        WHERE s2.user_id = $user_id";
$resultat3 = $conn->query($sql3);
$classmates_row = $resultat3->fetch(PDO::FETCH_OBJ);
$classmates_count = $classmates_row->cnt ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #080B14; color: #e8eaf0; }
        .card { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .sidebar { background: rgba(255,255,255,0.03); width: 250px; padding: 20px; }
        .btn { background: #6366f1; padding: 8px 16px; border-radius: 8px; color: white; }
    </style>
</head>
<body>

<div style="display: flex; min-height: 100vh;">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="color: #6366f1; margin-bottom: 30px;">EduSync</h2>
        <a href="dashboard.php" style="color: #818cf8; display: block; margin-bottom: 15px;">🏠 Dashboard</a>
        <a href="profile.php" style="color: gray; display: block; margin-bottom: 15px;">👤 Profile</a>
        <a href="courses.php" style="color: gray; display: block; margin-bottom: 15px;">📚 Courses</a>
        <a href="classmates.php" style="color: gray; display: block; margin-bottom: 30px;">👥 Classmates</a>
        
        <form action="../scripts/logout.php" method="POST">
            <button type="submit" style="color: #ef4444;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 20px;">Dashboard</h1>
        
        <!-- Welcome Card -->
        <div class="card">
            <h2>Welcome back, <?php echo htmlspecialchars($student_info->firstname ?? 'Student'); ?>! 👋</h2>
            <p>Class: <?php echo htmlspecialchars($student_info->class_name ?? 'Not assigned'); ?></p>
            <p>Student #: <?php echo htmlspecialchars($student_info->student_number ?? 'N/A'); ?></p>
        </div>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
            <div class="card">
                <h3 style="font-size: 36px; color: #818cf8;"><?php echo $total_courses; ?></h3>
                <p>Total Courses</p>
            </div>
            <div class="card">
                <h3 style="font-size: 36px; color: #2dd4bf;"><?php echo $total_hours; ?>h</h3>
                <p>Total Hours</p>
            </div>
            <div class="card">
                <h3 style="font-size: 36px; color: #c084fc;"><?php echo $classmates_count; ?></h3>
                <p>Classmates</p>
            </div>
        </div>

        <!-- My Courses -->
        <div class="card">
            <h3>My Courses</h3>
            <?php if(count($mes_cours) > 0): ?>
                <?php foreach($mes_cours as $cours): ?>
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.1); padding: 15px 0;">
                        <strong><?php echo htmlspecialchars($cours->title); ?></strong>
                        <p style="color: gray; font-size: 14px;"><?php echo htmlspecialchars($cours->description ?? 'No description'); ?></p>
                        <span style="color: #818cf8;">⏱ <?php echo $cours->total_hours; ?> hours</span>
                        <span style="margin-left: 15px;">👨‍🏫 Prof. <?php echo htmlspecialchars($cours->prof_lastname); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No courses enrolled yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>