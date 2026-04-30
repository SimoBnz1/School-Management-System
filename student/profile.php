<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.firstname, u.lastname, u.email,
        s.student_number, s.dateofbirth,
        c.name AS class_name, c.classroom_number
        FROM users u
        JOIN students s ON u.id = s.user_id
        JOIN classes c ON s.class_id = c.id
        WHERE u.id = $user_id";
$resultat = $conn->query($sql);
$profile = $resultat->fetch(PDO::FETCH_OBJ);

$sql2 = "SELECT COUNT(*) AS total FROM enrollments e
        JOIN students s ON e.student_id = s.id
        WHERE s.user_id = $user_id";
$resultat2 = $conn->query($sql2);
$stats = $resultat2->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #080B14; color: #e8eaf0; }
        .card { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .sidebar { background: rgba(255,255,255,0.03); width: 250px; padding: 20px; }
        .info-row { border-bottom: 1px solid rgba(255,255,255,0.1); padding: 12px; }
    </style>
</head>
<body>

<div style="display: flex; min-height: 100vh;">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="color: #6366f1; margin-bottom: 30px;">EduSync</h2>
        <a href="dashboard.php" style="color: gray; display: block; margin-bottom: 15px;">🏠 Dashboard</a>
        <a href="profile.php" style="color: #818cf8; display: block; margin-bottom: 15px;">👤 Profile</a>
        <a href="courses.php" style="color: gray; display: block; margin-bottom: 15px;">📚 Courses</a>
        <a href="classmates.php" style="color: gray; display: block; margin-bottom: 30px;">👥 Classmates</a>
        
        <form action="../scripts/logout.php" method="POST">
            <button type="submit" style="color: #ef4444;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 20px;">My Profile</h1>
        
        <?php if($profile): ?>
            <!-- Profile Header -->
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #6366f1, #a855f7); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 32px;">
                        <?php echo strtoupper(substr($profile->firstname, 0, 1) . substr($profile->lastname, 0, 1)); ?>
                    </span>
                </div>
                <h2><?php echo htmlspecialchars($profile->firstname . ' ' . $profile->lastname); ?></h2>
                <p><?php echo htmlspecialchars($profile->email); ?></p>
                <span style="background: #6366f1; padding: 4px 12px; border-radius: 20px; font-size: 12px; display: inline-block; margin-top: 10px;">
                    🎓 Student
                </span>
            </div>

            <!-- Stats -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                <div class="card" style="text-align: center;">
                    <h3 style="font-size: 36px; color: #818cf8;"><?php echo $stats->total ?? 0; ?></h3>
                    <p>Enrolled Courses</p>
                </div>
                <div class="card" style="text-align: center;">
                    <h3 style="font-size: 36px; color: #c084fc;"><?php echo htmlspecialchars($profile->class_name); ?></h3>
                    <p>Class</p>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="card">
                <h3>Personal Information</h3>
                <div class="info-row"><strong>First Name:</strong> <?php echo htmlspecialchars($profile->firstname); ?></div>
                <div class="info-row"><strong>Last Name:</strong> <?php echo htmlspecialchars($profile->lastname); ?></div>
                <div class="info-row"><strong>Email:</strong> <?php echo htmlspecialchars($profile->email); ?></div>
                <div class="info-row"><strong>Date of Birth:</strong> <?php echo date('d M Y', strtotime($profile->dateofbirth)); ?></div>
            </div>

            <!-- Academic Info -->
            <div class="card">
                <h3>Academic Information</h3>
                <div class="info-row"><strong>Student Number:</strong> <?php echo htmlspecialchars($profile->student_number); ?></div>
                <div class="info-row"><strong>Class:</strong> <?php echo htmlspecialchars($profile->class_name); ?></div>
                <div class="info-row"><strong>Classroom:</strong> Room <?php echo htmlspecialchars($profile->classroom_number); ?></div>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center;">
                <p>Profile not found. Please contact administration.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>