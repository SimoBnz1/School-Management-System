<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Student info + class
try {
    $req_info = "SELECT u.firstname, u.lastname, s.student_number, c.name AS class_name, c.classroom_number
                 FROM users u
                 JOIN students s ON u.id = s.user_id
                 JOIN classes c ON s.class_id = c.id
                 WHERE u.id = :user_id";
    $stmt_info = $conn->prepare($req_info);
    $stmt_info->execute([':user_id' => $user_id]);
    $student_info = $stmt_info->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $student_info = null;
}

// Enrolled courses + professor
try {
    $req_cours = "SELECT c.title, c.description, c.total_hours, e.status, e.enrolled_at,
                         p.firstname AS prof_firstname, p.lastname AS prof_lastname
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  JOIN students s ON e.student_id = s.id
                  JOIN users p ON c.user_id = p.id
                  WHERE s.user_id = :user_id";
    $stmt_cours = $conn->prepare($req_cours);
    $stmt_cours->execute([':user_id' => $user_id]);
    $mes_cours = $stmt_cours->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $mes_cours = [];
}

// Stats
$total_courses  = count($mes_cours);
$active_courses = count(array_filter($mes_cours, fn($c) => strtolower($c->status) === 'actif'));
$done_courses   = count(array_filter($mes_cours, fn($c) => strtolower($c->status) !== 'actif'));
$total_hours    = array_sum(array_column($mes_cours, 'total_hours'));

// Classmates count
try {
    $stmt_cm = $conn->prepare("
        SELECT COUNT(*) - 1 AS cnt
        FROM students s1
        JOIN students s2 ON s1.class_id = s2.class_id
        WHERE s2.user_id = :user_id
    ");
    $stmt_cm->execute([':user_id' => $user_id]);
    $classmates_count = max(0, $stmt_cm->fetchColumn());
} catch (PDOException $e) {
    $classmates_count = 0;
}

// Course icon helper
function getCourseIcon($title) {
    $t = strtolower($title);
    if (str_contains($t, 'html') || str_contains($t, 'css') || str_contains($t, 'web')) return '🌐';
    if (str_contains($t, 'javascript') || str_contains($t, 'js')) return '⚡';
    if (str_contains($t, 'php')) return '🐘';
    if (str_contains($t, 'sql') || str_contains($t, 'base') || str_contains($t, 'data')) return '🗄️';
    if (str_contains($t, 'python')) return '🐍';
    if (str_contains($t, 'réseau') || str_contains($t, 'reseau') || str_contains($t, 'network')) return '🔗';
    return '📚';
}

$course_colors = [
    ['bg' => 'rgba(99,102,241,0.12)',  'border' => 'rgba(99,102,241,0.3)',  'accent' => '#818cf8'],
    ['bg' => 'rgba(168,85,247,0.12)', 'border' => 'rgba(168,85,247,0.3)', 'accent' => '#c084fc'],
    ['bg' => 'rgba(236,72,153,0.12)', 'border' => 'rgba(236,72,153,0.3)', 'accent' => '#f472b6'],
    ['bg' => 'rgba(20,184,166,0.12)', 'border' => 'rgba(20,184,166,0.3)', 'accent' => '#2dd4bf'],
    ['bg' => 'rgba(245,158,11,0.12)', 'border' => 'rgba(245,158,11,0.3)', 'accent' => '#fbbf24'],
    ['bg' => 'rgba(59,130,246,0.12)', 'border' => 'rgba(59,130,246,0.3)', 'accent' => '#60a5fa'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – EduSync</title>
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

        /* Sidebar */
        .sidebar-link { transition: all 0.2s; border-radius: 10px; }
        .sidebar-link:hover { background: rgba(99,102,241,0.15); color: white; }
        .sidebar-link.active { background: rgba(99,102,241,0.2); color: #818cf8; border-left: 3px solid #6366f1; }

        /* Stat cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(168,85,247,0.05) 100%);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            border-color: rgba(99,102,241,0.45);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99,102,241,0.18);
        }

        /* Course items */
        .course-row {
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.03);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .course-row:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(99,102,241,0.3);
            transform: translateX(4px);
        }

        /* Info rows */
        .info-row {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: background 0.2s;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row:hover { background: rgba(255,255,255,0.03); }

        /* Avatar ring */
        .avatar-ring {
            background: conic-gradient(from 0deg, #6366f1, #a855f7, #ec4899, #6366f1);
            padding: 2px;
            border-radius: 50%;
        }

        /* Welcome banner */
        .banner {
            background: linear-gradient(135deg, rgba(99,102,241,0.25) 0%, rgba(168,85,247,0.15) 50%, rgba(236,72,153,0.1) 100%);
            border: 1px solid rgba(99,102,241,0.3);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }
        .banner::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(99,102,241,0.3), transparent 70%);
            pointer-events: none;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.12s; opacity: 0; }
        .d3 { animation-delay: 0.20s; opacity: 0; }
        .d4 { animation-delay: 0.28s; opacity: 0; }

        .stagger > * { opacity: 0; animation: fadeUp 0.45s ease forwards; }
        .stagger > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger > *:nth-child(2) { animation-delay: 0.10s; }
        .stagger > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger > *:nth-child(4) { animation-delay: 0.20s; }
        .stagger > *:nth-child(5) { animation-delay: 0.25s; }
        .stagger > *:nth-child(6) { animation-delay: 0.30s; }
    </style>
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    <!-- ── Sidebar ── -->
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

        <!-- Role badge -->
        <div class="mb-8 px-3 py-2 rounded-lg bg-indigo-500/10 border border-indigo-500/20">
            <span class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Student Portal</span>
        </div>

        <!-- Nav links -->
        <nav class="flex flex-col gap-1 flex-1">
            <a href="dashboard.php" class="sidebar-link active flex items-center gap-3 px-3 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="profile.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
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

    <!-- ── Main ── -->
    <main class="flex-1 p-8 overflow-auto">

        <!-- Ambient blobs -->
        <div class="fixed top-20 right-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="fixed bottom-20 left-80 w-64 h-64 bg-purple-600/10 rounded-full blur-[80px] pointer-events-none"></div>

        <!-- Page header -->
        <div class="mb-8 fade-up">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-1">Student Portal</p>
            <h1 class="syne text-3xl font-extrabold text-white">Dashboard</h1>
        </div>

        <!-- Welcome banner -->
        <div class="banner p-7 mb-8 fade-up d1 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="text-indigo-300 text-sm font-semibold mb-1">Welcome back 👋</p>
                <h2 class="syne text-2xl font-bold text-white mb-1">
                    <?php echo htmlspecialchars(($student_info->firstname ?? '') . ' ' . ($student_info->lastname ?? '')); ?>
                </h2>
                <p class="text-gray-400 text-sm">Track your modules, professors and your progress right here.</p>
            </div>
            <div class="avatar-ring shrink-0">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-800 to-purple-900 flex items-center justify-center">
                    <span class="syne text-lg font-bold text-white">
                        <?php echo strtoupper(substr($student_info->firstname ?? 'S', 0, 1) . substr($student_info->lastname ?? '', 0, 1)); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-up d2">
            <div class="stat-card">
                <div class="text-3xl font-bold syne text-indigo-400 mb-1"><?php echo $total_courses; ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Total Courses</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold syne text-emerald-400 mb-1"><?php echo $active_courses; ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Active</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold syne text-gray-400 mb-1"><?php echo $done_courses; ?></div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Completed</div>
            </div>
            <div class="stat-card">
                <div class="text-3xl font-bold syne text-purple-400 mb-1"><?php echo $total_hours; ?>h</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Total Hours</div>
            </div>
        </div>

        <!-- Content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ── Student Info card ── -->
            <div class="lg:col-span-1 glass rounded-2xl p-6 h-fit fade-up d3">
                <h3 class="syne font-semibold text-white mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center">
                        <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    My Information
                </h3>

                <?php if ($student_info): ?>
                <div class="rounded-xl overflow-hidden border border-white/5">
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Full Name</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($student_info->firstname . ' ' . $student_info->lastname); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Student #</span>
                        <span class="font-mono text-sm font-bold text-indigo-400"><?php echo htmlspecialchars($student_info->student_number); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Class</span>
                        <span class="text-sm font-medium text-white"><?php echo htmlspecialchars($student_info->class_name); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Classroom</span>
                        <span class="text-sm font-medium text-white">Room <?php echo htmlspecialchars($student_info->classroom_number); ?></span>
                    </div>
                    <div class="info-row flex justify-between items-center px-4 py-3">
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Classmates</span>
                        <span class="text-sm font-bold text-purple-400"><?php echo $classmates_count; ?></span>
                    </div>
                </div>

                <!-- Quick links -->
                <div class="mt-5 grid grid-cols-2 gap-2">
                    <a href="profile.php" class="flex items-center justify-center gap-1.5 py-2 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold hover:bg-indigo-500/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile
                    </a>
                    <a href="classmates.php" class="flex items-center justify-center gap-1.5 py-2 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-semibold hover:bg-purple-500/20 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Classmates
                    </a>
                </div>
                <?php else: ?>
                <p class="text-red-400 text-sm">Information not found. Please contact administration.</p>
                <?php endif; ?>
            </div>

            <!-- ── My Modules ── -->
            <div class="lg:col-span-2 glass rounded-2xl p-6 fade-up d4">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="syne font-semibold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-md bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        My Modules
                    </h3>
                    <a href="courses.php" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition-colors">View all →</a>
                </div>

                <div class="space-y-3 stagger">
                    <?php if (count($mes_cours) > 0):
                        foreach ($mes_cours as $i => $cours):
                            $color = $course_colors[$i % count($course_colors)];
                            $icon = getCourseIcon($cours->title);
                            $isActive = strtolower($cours->status) === 'actif';
                    ?>
                    <div class="course-row" style="border-color: <?php echo $color['border']; ?>;">
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0"
                             style="background: <?php echo $color['bg']; ?>;">
                            <?php echo $icon; ?>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="syne font-bold text-white text-sm leading-tight truncate"><?php echo htmlspecialchars($cours->title); ?></h4>
                            <p class="text-xs text-gray-500 mt-0.5 truncate"><?php echo htmlspecialchars($cours->description ?: 'No description'); ?></p>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-xs text-gray-400">⏱ <?php echo $cours->total_hours; ?>h</span>
                                <span class="text-xs font-medium" style="color: <?php echo $color['accent']; ?>">
                                    👨‍🏫 Prof. <?php echo htmlspecialchars($cours->prof_lastname); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Status badge -->
                        <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-bold <?php echo $isActive ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-gray-500/15 text-gray-400 border border-gray-500/30'; ?>">
                            <?php echo htmlspecialchars($cours->status); ?>
                        </span>
                    </div>
                    <?php endforeach;
                    else: ?>
                    <div class="text-center py-12">
                        <div class="text-5xl mb-3">📭</div>
                        <p class="text-gray-500 text-sm">No modules enrolled yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>
