<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les cours
$sql = "SELECT c.id, c.title, c.description, c.total_hours, e.status, e.enrolled_at,
        p.firstname AS prof_firstname, p.lastname AS prof_lastname
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN students s ON e.student_id = s.id
        JOIN users p ON c.user_id = p.id
        WHERE s.user_id = $user_id
        ORDER BY e.enrolled_at DESC";
$resultat = $conn->query($sql);
$courses = $resultat->fetchAll(PDO::FETCH_OBJ);

// Compter les stats
$total = count($courses);
$total_hours = 0;
foreach($courses as $c) {
    $total_hours += $c->total_hours;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #080B14; color: #e8eaf0; }
        .card { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .sidebar { background: rgba(255,255,255,0.03); width: 250px; padding: 20px; }
        .course-item { border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 15px; margin-bottom: 15px; }
        .course-item:hover { background: rgba(255,255,255,0.08); }
    </style>
</head>
<body>

<div style="display: flex; min-height: 100vh;">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="color: #6366f1; margin-bottom: 30px;">EduSync</h2>
        <a href="dashboard.php" style="color: gray; display: block; margin-bottom: 15px;">🏠 Dashboard</a>
        <a href="profile.php" style="color: gray; display: block; margin-bottom: 15px;">👤 Profile</a>
        <a href="courses.php" style="color: #818cf8; display: block; margin-bottom: 15px;">📚 Courses</a>
        <a href="classmates.php" style="color: gray; display: block; margin-bottom: 30px;">👥 Classmates</a>
        
        <form action="../scripts/logout.php" method="POST">
            <button type="submit" style="color: #ef4444;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 20px;">My Courses</h1>
        
        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div class="card" style="text-align: center;">
                <h3 style="font-size: 36px; color: #818cf8;"><?php echo $total; ?></h3>
                <p>Total Courses</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3 style="font-size: 36px; color: #2dd4bf;"><?php echo $total_hours; ?>h</h3>
                <p>Total Hours</p>
            </div>
        </div>

        <!-- Courses List -->
        <?php if(count($courses) > 0): ?>
            <?php foreach($courses as $course): ?>
                <div class="course-item">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3 style="font-size: 18px; margin-bottom: 8px;"><?php echo htmlspecialchars($course->title); ?></h3>
                            <p style="color: gray; font-size: 14px; margin-bottom: 10px;"><?php echo htmlspecialchars($course->description ?? 'No description available.'); ?></p>
                            <div style="display: flex; gap: 20px;">
                                <span style="color: #818cf8;">⏱ <?php echo $course->total_hours; ?> hours</span>
                                <span>👨‍🏫 Prof. <?php echo htmlspecialchars($course->prof_lastname); ?></span>
                            </div>
                        </div>
                        <div>
                            <span style="background: <?php echo strtolower($course->status) == 'actif' ? '#10b981' : '#6b7280'; ?>; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                <?php echo htmlspecialchars($course->status); ?>
                            </span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 12px; color: gray;">
                        Enrolled: <?php echo date('d M Y', strtotime($course->enrolled_at)); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="text-align: center;">
                <p>You're not enrolled in any courses yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>