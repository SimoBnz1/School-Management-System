<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// US24 - Student profile + class info
try {
    $stmt = $conn->prepare("
        SELECT u.firstname, u.lastname, u.email,
               s.student_number, s.dateofbirth,
               c.name AS class_name, c.classroom_number
        FROM users u
        JOIN students s ON u.id = s.user_id
        JOIN classes c ON s.class_id = c.id
        WHERE u.id = :user_id
    ");
    $stmt->execute([':user_id' => $user_id]);
    $profile = $stmt->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $profile = null;
}

// Count enrolled courses
try {
    $stmt2 = $conn->prepare("
        SELECT COUNT(*) AS total_courses,
               SUM(CASE WHEN e.status = 'Actif' THEN 1 ELSE 0 END) AS active_courses
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        WHERE s.user_id = :user_id
    ");
    $stmt2->execute([':user_id' => $user_id]);
    $stats = $stmt2->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $stats = null;
}

// Count classmates
try {
    $stmt3 = $conn->prepare("
        SELECT COUNT(*) - 1 AS classmates_count
        FROM students s1
        JOIN students s2 ON s1.class_id = s2.class_id
        WHERE s2.user_id = :user_id
    ");
    $stmt3->execute([':user_id' => $user_id]);
    $classmates_row = $stmt3->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $classmates_row = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, .syne { font-family: 'Syne', sans-serif; }
        body { background: #080B14; color: #e8eaf0; }

        .glass {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
        }
        .accent-glow {
            box-shadow: 0 0 40px rgba(99,102,241,0.15);
        }
        .avatar-ring {
            background: conic-gradient(from 0deg, #6366f1, #a855f7, #ec4899, #6366f1);
            padding: 3px;
            border-radius: 50%;
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(168,85,247,0.05) 100%);
            border: 1px solid rgba(99,102,241,0.2);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            border-color: rgba(99,102,241,0.5);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99,102,241,0.2);
        }
        .info-row {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.2s;
        }
        .info-row:hover { background: rgba(255,255,255,0.03); }

        /* Sidebar */
        .sidebar-link { transition: all 0.2s; border-radius: 10px; }
        .sidebar-link:hover { background: rgba(99,102,241,0.15); color: white; }
        .sidebar-link.active { background: rgba(99,102,241,0.2); color: #818cf8; border-left: 3px solid #6366f1; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
    </style>
</head>
<body class="min-h-screen">

<!-- Layout -->
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 shrink-0 glass border-r border-white/5 flex flex-col py-8 px-5 sticky top-0 h-screen">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="syne font-bold text-lg bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">EduSync</span>
        </div>

        <!-- Student badge -->
        <div class="mb-8 px-3 py-2 rounded-lg bg-indigo-500/10 border border-indigo-500/20">
            <span class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Student Portal</span>
        </div>

        <!-- Nav -->
        <nav class="flex flex-col gap-1 flex-1">
            <a href="dashboard.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="profile.php" class="sidebar-link active flex items-center gap-3 px-3 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-sm font-medium">My Profile</span>
            </a>
            <a href="courses.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-sm font-medium">My Courses</span>
            </a>
            <a href="classmates.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-medium">Classmates</span>
            </a>
        </nav>

        <!-- Logout -->
        <form action="../scripts/logout.php" method="POST" class="mt-auto">
            <button type="submit" name="logout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-red-500/10 transition-all text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8 overflow-auto">

        <!-- Ambient blobs -->
        <div class="fixed top-20 right-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="fixed bottom-20 left-80 w-64 h-64 bg-purple-600/10 rounded-full blur-[80px] pointer-events-none"></div>

        <!-- Page header -->
        <div class="mb-8 fade-up">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-1">Student Portal</p>
            <h1 class="syne text-3xl font-800 text-white">My Profile</h1>
        </div>

        <?php if ($profile): ?>

        <!-- Profile hero -->
        <div class="glass rounded-2xl p-8 mb-6 accent-glow fade-up delay-1">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <!-- Avatar -->
                <div class="avatar-ring shrink-0">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-800 to-purple-900 flex items-center justify-center">
                        <span class="syne text-2xl font-bold text-white">
                            <?php echo strtoupper(substr($profile->firstname, 0, 1) . substr($profile->lastname, 0, 1)); ?>
                        </span>
                    </div>
                </div>
                <!-- Info -->
                <div class="text-center sm:text-left flex-1">
                    <h2 class="syne text-2xl font-bold text-white mb-1">
                        <?php echo htmlspecialchars($profile->firstname . ' ' . $profile->lastname); ?>
                    </h2>
                    <p class="text-gray-400 text-sm mb-3"><?php echo htmlspecialchars($profile->email); ?></p>
                    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                        <span class="px-3 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-semibold">
                            🎓 Student
                        </span>
                        <span class="px-3 py-1 rounded-full bg-purple-500/15 border border-purple-500/30 text-purple-300 text-xs font-semibold">
                            <?php echo htmlspecialchars($profile->class_name); ?>
                        </span>
                        <span class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold">
                            # <?php echo htmlspecialchars($profile->student_number); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 fade-up delay-2">
            <div class="stat-card rounded-xl p-5 text-center">
                <div class="text-3xl font-bold syne text-indigo-400 mb-1"><?php echo $stats->total_courses ?? 0; ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Enrolled Courses</div>
            </div>
            <div class="stat-card rounded-xl p-5 text-center">
                <div class="text-3xl font-bold syne text-purple-400 mb-1"><?php echo $stats->active_courses ?? 0; ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Active Modules</div>
            </div>
            <div class="stat-card rounded-xl p-5 text-center">
                <div class="text-3xl font-bold syne text-pink-400 mb-1"><?php echo max(0, $classmates_row->classmates_count ?? 0); ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Classmates</div>
            </div>
        </div>

        <!-- Details grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-up delay-3">

            <!-- Personal Info -->
            <div class="glass rounded-2xl p-6">
                <h3 class="syne font-semibold text-white mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center">
                        <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    Personal Information
                </h3>
                <div class="space-y-0 rounded-xl overflow-hidden border border-white/5">
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">First Name</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($profile->firstname); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Last Name</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($profile->lastname); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Email</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($profile->email); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Date of Birth</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars(date('d M Y', strtotime($profile->dateofbirth))); ?></span>
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="glass rounded-2xl p-6">
                <h3 class="syne font-semibold text-white mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    Academic Information
                </h3>
                <div class="space-y-0 rounded-xl overflow-hidden border border-white/5">
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Student Number</span>
                        <span class="font-mono text-sm font-bold text-indigo-400"><?php echo htmlspecialchars($profile->student_number); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Class / Program</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($profile->class_name); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Classroom</span>
                        <span class="text-sm font-medium text-white">Room <?php echo htmlspecialchars($profile->classroom_number); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Role</span>
                        <span class="px-2 py-0.5 rounded-full bg-indigo-500/15 text-indigo-300 text-xs font-semibold">Student</span>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="glass rounded-2xl p-12 text-center">
            <div class="text-5xl mb-4">⚠️</div>
            <p class="text-gray-400">Profile not found. Please contact administration.</p>
        </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
