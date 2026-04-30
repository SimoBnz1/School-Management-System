<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// US26 - Get classmates (same class, excluding self)
try {
    // First get current student's class
    $stmt_class = $conn->prepare("
        SELECT s.class_id, c.name AS class_name, c.classroom_number
        FROM students s
        JOIN classes c ON s.class_id = c.id
        WHERE s.user_id = :user_id
    ");
    $stmt_class->execute([':user_id' => $user_id]);
    $my_class = $stmt_class->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $my_class = null;
}

$classmates = [];
if ($my_class) {
    try {
        // Get all students in the same class excluding self
        $stmt_mates = $conn->prepare("
            SELECT u.firstname, u.lastname, u.email,
                   s.student_number, s.dateofbirth, s.id AS student_id
            FROM students s
            JOIN users u ON s.user_id = u.id
            WHERE s.class_id = :class_id
              AND s.user_id != :user_id
            ORDER BY u.lastname ASC
        ");
        $stmt_mates->execute([
            ':class_id' => $my_class->class_id,
            ':user_id'  => $user_id
        ]);
        $classmates = $stmt_mates->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $classmates = [];
    }
}

// Avatar colors for classmates
$avatar_colors = [
    ['from' => '#6366f1', 'to' => '#8b5cf6'],
    ['from' => '#a855f7', 'to' => '#ec4899'],
    ['from' => '#14b8a6', 'to' => '#06b6d4'],
    ['from' => '#f59e0b', 'to' => '#ef4444'],
    ['from' => '#10b981', 'to' => '#3b82f6'],
    ['from' => '#f43f5e', 'to' => '#fb923c'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classmates – EduSync</title>
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

        .classmate-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            text-align: center;
        }
        .classmate-card:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(99,102,241,0.3);
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(99,102,241,0.15);
        }

        .search-input {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: white;
            transition: all 0.2s;
        }
        .search-input:focus {
            outline: none;
            border-color: rgba(99,102,241,0.5);
            background: rgba(99,102,241,0.08);
        }
        .search-input::placeholder { color: rgba(255,255,255,0.25); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }

        .stagger-grid > * {
            opacity: 0;
            animation: fadeUp 0.5s ease forwards;
        }
        <?php foreach(range(1,12) as $n): ?>
        .stagger-grid > *:nth-child(<?php echo $n; ?>) { animation-delay: <?php echo $n * 0.06; ?>s; }
        <?php endforeach; ?>

        .class-badge {
            background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(168,85,247,0.1));
            border: 1px solid rgba(99,102,241,0.3);
        }
    </style>
</head>
<body class="min-h-screen">

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
            <a href="courses.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-sm font-medium">My Courses</span>
            </a>
            <a href="classmates.php" class="sidebar-link active flex items-center gap-3 px-3 py-2.5">
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

        <!-- Header -->
        <div class="mb-6 fade-up">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-1">Student Portal</p>
            <h1 class="syne text-3xl font-800 text-white">My Classmates</h1>
        </div>

        <!-- Class info banner -->
        <?php if ($my_class): ?>
        <div class="class-badge rounded-2xl p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 fade-up">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-2xl shrink-0">🏫</div>
            <div class="flex-1">
                <h2 class="syne font-bold text-white text-lg"><?php echo htmlspecialchars($my_class->class_name); ?></h2>
                <p class="text-gray-400 text-sm">Room <?php echo htmlspecialchars($my_class->classroom_number); ?> &nbsp;·&nbsp; <?php echo count($classmates); ?> classmate<?php echo count($classmates) !== 1 ? 's' : ''; ?></p>
            </div>
            <div class="flex -space-x-2">
                <?php foreach(array_slice($classmates, 0, 5) as $i => $cm):
                    $c = $avatar_colors[$i % count($avatar_colors)];
                ?>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white border-2 border-[#080B14]"
                     style="background: linear-gradient(135deg, <?php echo $c['from']; ?>, <?php echo $c['to']; ?>)">
                    <?php echo strtoupper(substr($cm->firstname, 0, 1)); ?>
                </div>
                <?php endforeach; ?>
                <?php if (count($classmates) > 5): ?>
                <div class="w-8 h-8 rounded-full bg-white/10 border-2 border-[#080B14] flex items-center justify-center text-xs text-gray-400 font-semibold">
                    +<?php echo count($classmates) - 5; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Search -->
        <div class="mb-6 fade-up">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search classmates..."
                    class="search-input w-full pl-10 pr-4 py-2.5 text-sm"
                    oninput="filterClassmates(this.value)"
                >
            </div>
        </div>

        <!-- Grid -->
        <?php if (count($classmates) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 stagger-grid" id="classmatesGrid">
            <?php foreach ($classmates as $i => $cm):
                $c = $avatar_colors[$i % count($avatar_colors)];
                $initials = strtoupper(substr($cm->firstname, 0, 1) . substr($cm->lastname, 0, 1));
                $age = date_diff(date_create($cm->dateofbirth), date_create('today'))->y;
            ?>
            <div class="classmate-card" data-name="<?php echo strtolower($cm->firstname . ' ' . $cm->lastname); ?>">
                <!-- Avatar -->
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-xl font-bold text-white"
                     style="background: linear-gradient(135deg, <?php echo $c['from']; ?>, <?php echo $c['to']; ?>); box-shadow: 0 4px 20px <?php echo $c['from']; ?>40">
                    <?php echo $initials; ?>
                </div>
                <!-- Name -->
                <h3 class="syne font-bold text-white mb-0.5">
                    <?php echo htmlspecialchars($cm->firstname . ' ' . $cm->lastname); ?>
                </h3>
                <!-- Student number -->
                <p class="text-xs font-mono text-indigo-400 mb-3"><?php echo htmlspecialchars($cm->student_number); ?></p>
                <!-- Details -->
                <div class="space-y-1.5 text-left">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate"><?php echo htmlspecialchars($cm->email); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span><?php echo $age; ?> years old</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty search state -->
        <div id="noResults" class="hidden text-center py-16">
            <div class="text-5xl mb-3">🔍</div>
            <p class="text-gray-500">No classmates found matching your search.</p>
        </div>

        <?php else: ?>
        <div class="glass rounded-2xl p-16 text-center">
            <div class="text-6xl mb-4">👤</div>
            <h3 class="syne text-xl font-bold text-white mb-2">No classmates yet</h3>
            <p class="text-gray-500 text-sm">You're the only one in your class, or your class hasn't been set up yet.</p>
        </div>
        <?php endif; ?>
    </main>
</div>

<script>
function filterClassmates(query) {
    const cards = document.querySelectorAll('#classmatesGrid .classmate-card');
    const noResults = document.getElementById('noResults');
    let visible = 0;
    cards.forEach(card => {
        const name = card.dataset.name || '';
        if (name.includes(query.toLowerCase())) {
            card.style.display = '';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });
    noResults.classList.toggle('hidden', visible > 0);
}
</script>

</body>
</html>
