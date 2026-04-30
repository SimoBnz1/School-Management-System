<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// US25 + US27 - Enrolled courses with professor name, description, total_hours
try {
    $stmt = $conn->prepare("
        SELECT c.id, c.title, c.description, c.total_hours,
               e.status, e.enrolled_at,
               p.firstname AS prof_firstname, p.lastname AS prof_lastname
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN students s ON e.student_id = s.id
        JOIN users p ON c.user_id = p.id
        WHERE s.user_id = :user_id
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([':user_id' => $user_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $courses = [];
}

// Get student name
$firstname = $_SESSION['firstname'] ?? 'Student';
$lastname = $_SESSION['lastname'] ?? '';

// Stats
$total = count($courses);
$active = array_filter($courses, fn($c) => strtolower($c->status) === 'actif');
$done = array_filter($courses, fn($c) => strtolower($c->status) === 'terminé');
$total_hours = array_sum(array_column($courses, 'total_hours'));

// Course icons mapping (fallback to emoji based on title keywords)
function getCourseIcon($title) {
    $title = strtolower($title);
    if (str_contains($title, 'html') || str_contains($title, 'css') || str_contains($title, 'web')) return '🌐';
    if (str_contains($title, 'js') || str_contains($title, 'javascript')) return '⚡';
    if (str_contains($title, 'php')) return '🐘';
    if (str_contains($title, 'base') || str_contains($title, 'sql') || str_contains($title, 'data')) return '🗄️';
    if (str_contains($title, 'python')) return '🐍';
    if (str_contains($title, 'java')) return '☕';
    if (str_contains($title, 'reseau') || str_contains($title, 'réseau') || str_contains($title, 'network')) return '🔗';
    if (str_contains($title, 'math')) return '📐';
    return '📚';
}

// Color per course index
$colors = [
    ['bg' => 'rgba(99,102,241,0.15)', 'border' => 'rgba(99,102,241,0.35)', 'accent' => '#818cf8'],
    ['bg' => 'rgba(168,85,247,0.15)', 'border' => 'rgba(168,85,247,0.35)', 'accent' => '#c084fc'],
    ['bg' => 'rgba(236,72,153,0.15)', 'border' => 'rgba(236,72,153,0.35)', 'accent' => '#f472b6'],
    ['bg' => 'rgba(20,184,166,0.15)', 'border' => 'rgba(20,184,166,0.35)', 'accent' => '#2dd4bf'],
    ['bg' => 'rgba(245,158,11,0.15)', 'border' => 'rgba(245,158,11,0.35)', 'accent' => '#fbbf24'],
    ['bg' => 'rgba(59,130,246,0.15)', 'border' => 'rgba(59,130,246,0.35)', 'accent' => '#60a5fa'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses – EduSync</title>
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
        .sidebar-link { transition: all 0.2s; border-radius: 10px; }
        .sidebar-link:hover { background: rgba(99,102,241,0.15); color: white; }
        .sidebar-link.active { background: rgba(99,102,241,0.2); color: #818cf8; border-left: 3px solid #6366f1; }



        
        .course-card {
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .course-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.02) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .course-card:hover::before { opacity: 1; }
        .course-card:hover {
            transform: translateY(-6px);
        }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 50;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #0f1424;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 32px;
            max-width: 520px;
            width: 90%;
            transform: scale(0.95) translateY(20px);
            transition: all 0.3s;
        }
        .modal-overlay.open .modal-box {
            transform: scale(1) translateY(0);
        }

        .stat-pill {
            background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(168,85,247,0.05));
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 12px;
            padding: 16px 20px;
            text-align: center;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .stagger > * {
            opacity: 0;
            animation: fadeUp 0.5s ease forwards;
        }
        .stagger > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger > *:nth-child(2) { animation-delay: 0.1s; }
        .stagger > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger > *:nth-child(4) { animation-delay: 0.2s; }
        .stagger > *:nth-child(5) { animation-delay: 0.25s; }
        .stagger > *:nth-child(6) { animation-delay: 0.3s; }
    </style>
</head>
<body class="min-h-screen">

<!-- Modal (US27 - Course Details) -->
<div class="modal-overlay" id="courseModal">
    <div class="modal-box">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-3">
                <div id="modal-icon" class="text-4xl"></div>
                <div>
                    <h3 id="modal-title" class="syne text-xl font-bold text-white"></h3>
                    <p id="modal-prof" class="text-sm text-gray-400 mt-0.5"></p>
                </div>
            </div>
            <button onclick="closeModal()" class="text-gray-500 hover:text-white transition-colors p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="rounded-xl bg-white/5 border border-white/5 p-4 mb-5">
            <p class="text-sm text-gray-300 leading-relaxed" id="modal-description">—</p>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="text-center p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
                <div id="modal-hours" class="syne text-2xl font-bold text-indigo-400"></div>
                <div class="text-xs text-gray-500 mt-1">Total Hours</div>
            </div>
            <div class="text-center p-3 rounded-xl bg-purple-500/10 border border-purple-500/20">
                <div id="modal-status" class="syne text-sm font-bold text-purple-400"></div>
                <div class="text-xs text-gray-500 mt-1">Status</div>
            </div>
            <div class="text-center p-3 rounded-xl bg-pink-500/10 border border-pink-500/20">
                <div id="modal-enrolled" class="syne text-xs font-bold text-pink-400"></div>
                <div class="text-xs text-gray-500 mt-1">Enrolled</div>
            </div>
        </div>
    </div>
</div>

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 shrink-0 glass border-r border-white/5 flex flex-col py-8 px-5 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="syne font-bold text-lg bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">EduSync</span>
        </div>
        <div class="mb-8 px-3 py-2 rounded-lg bg-indigo-500/10 border border-indigo-500/20">
            <span class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Student Portal</span>
        </div>
        <nav class="flex flex-col gap-1 flex-1">
            <a href="dashboard.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="profile.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-sm font-medium">My Profile</span>
            </a>
            <a href="courses.php" class="sidebar-link active flex items-center gap-3 px-3 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-sm font-medium">My Courses</span>
            </a>
            <a href="classmates.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-medium">Classmates</span>
            </a>
        </nav>
        <form action="../scripts/logout.php" method="POST" class="mt-auto">
            <button type="submit" name="logout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-red-500/10 transition-all text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8 overflow-auto">

        <div class="fixed top-20 right-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="fixed bottom-20 left-80 w-64 h-64 bg-purple-600/10 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="mb-8 fade-up">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-1">Student Portal</p>
            <h1 class="syne text-3xl font-800 text-white">My Courses</h1>
            <p class="text-gray-500 text-sm mt-1">Click on any course to view full details</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 fade-up">
            <div class="stat-pill">
                <div class="syne text-2xl font-bold text-white"><?php echo $total; ?></div>
                <div class="text-xs text-gray-500 mt-1">Total Courses</div>
            </div>
            <div class="stat-pill">
                <div class="syne text-2xl font-bold text-emerald-400"><?php echo count($active); ?></div>
                <div class="text-xs text-gray-500 mt-1">Active</div>
            </div>
            <div class="stat-pill">
                <div class="syne text-2xl font-bold text-gray-400"><?php echo count($done); ?></div>
                <div class="text-xs text-gray-500 mt-1">Completed</div>
            </div>
            <div class="stat-pill">
                <div class="syne text-2xl font-bold text-indigo-400"><?php echo $total_hours; ?>h</div>
                <div class="text-xs text-gray-500 mt-1">Total Hours</div>
            </div>
        </div>

        <!-- Courses Grid -->
        <?php if (count($courses) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 stagger">
            <?php foreach ($courses as $i => $course):
                $color = $colors[$i % count($colors)];
                $icon = getCourseIcon($course->title);
                $isActive = strtolower($course->status) === 'actif';
                $enrolled_formatted = date('d M Y', strtotime($course->enrolled_at));
            ?>
            <div class="course-card"
                 style="background: <?php echo $color['bg']; ?>; border: 1px solid <?php echo $color['border']; ?>; box-shadow: 0 4px 30px <?php echo str_replace('0.35', '0.1', $color['border']); ?>;"
                 onclick="openModal(
                    '<?php echo htmlspecialchars($icon); ?>',
                    '<?php echo addslashes(htmlspecialchars($course->title)); ?>',
                    '<?php echo addslashes(htmlspecialchars($course->prof_firstname . ' ' . $course->prof_lastname)); ?>',
                    '<?php echo addslashes(htmlspecialchars($course->description ?: 'No description available.')); ?>',
                    <?php echo (int)$course->total_hours; ?>,
                    '<?php echo htmlspecialchars($course->status); ?>',
                    '<?php echo $enrolled_formatted; ?>'
                 )">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-3xl"><?php echo $icon; ?></span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold <?php echo $isActive ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30'; ?>">
                        <?php echo htmlspecialchars($course->status); ?>
                    </span>
                </div>

                <h3 class="syne font-bold text-white text-lg mb-1 leading-tight"><?php echo htmlspecialchars($course->title); ?></h3>
                <p class="text-xs text-gray-400 mb-4 line-clamp-2"><?php echo htmlspecialchars($course->description ?: 'Click to view details'); ?></p>

                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center">
                            <span class="text-xs font-bold" style="color: <?php echo $color['accent']; ?>">
                                <?php echo strtoupper(substr($course->prof_lastname, 0, 1)); ?>
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">Prof. <?php echo htmlspecialchars($course->prof_lastname); ?></span>
                    </div>
                    <span class="text-xs font-bold" style="color: <?php echo $color['accent']; ?>">
                        ⏱ <?php echo $course->total_hours; ?>h
                    </span>
                </div>

                <!-- Hover hint -->
                <p class="text-xs text-center mt-3 opacity-40">Click for details</p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="glass rounded-2xl p-16 text-center">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="syne text-xl font-bold text-white mb-2">No courses yet</h3>
            <p class="text-gray-500 text-sm">You're not enrolled in any courses. Contact your administrator.</p>
        </div>
        <?php endif; ?>
    </main>
</div>

<script>
function openModal(icon, title, prof, description, hours, status, enrolled) {
    document.getElementById('modal-icon').textContent = icon;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-prof').textContent = '👨‍🏫 Prof. ' + prof;
    document.getElementById('modal-description').textContent = description;
    document.getElementById('modal-hours').textContent = hours + 'h';
    document.getElementById('modal-status').textContent = status;
    document.getElementById('modal-enrolled').textContent = enrolled;
    document.getElementById('courseModal').classList.add('open');
}
function closeModal() {
    document.getElementById('courseModal').classList.remove('open');
}
document.getElementById('courseModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

</body>
</html>
