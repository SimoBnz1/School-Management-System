<?php
require '../scripts/connection.php';


// session_start();
// require '../scripts/connection.php';

// $user_id = $_SESSION['id'] ?? null;

// if (!$user_id) {
//     die("User not logged in");
// }

// /* نجيب role ديال user */
// $stmt = $conn->prepare("SELECT role_id FROM users WHERE id = ?");
// $stmt->execute([$user_id]);
// $user = $stmt->fetch();

// /* إلا ماشي prof (role_id = 2) */
// if ($user['role_id'] != 2) {
//     die("Access denied ❌ (Only Prof)");
// }
/* total students */
$stmt = $conn->query("SELECT COUNT(*) as total FROM students");
$total_students = $stmt->fetch()['total'];

/* total courses */
$stmt = $conn->query("SELECT COUNT(*) as total FROM courses");
$total_courses = $stmt->fetch()['total'];

/* total classes */
$stmt = $conn->query("SELECT COUNT(*) as total FROM classes");
$total_classes = $stmt->fetch()['total'];

/* courses list */
$stmt = $conn->query("SELECT title, total_hours FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

    <!-- Students Card -->
    <div class="rounded-3xl p-8 bg-gradient-to-br from-[#1a1a2e] to-[#16213e] border border-white/10 shadow-xl hover:scale-[1.02] transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-white/60">Students</p>
                <h2 class="text-4xl font-bold mt-3 text-white"><?= $total_students ?></h2>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-purple-500/20 text-xl">
                👨‍🎓
            </div>
        </div>
    </div>

    <!-- Courses Card -->
    <div class="rounded-3xl p-8 bg-gradient-to-br from-[#0f2027] to-[#203a43] border border-white/10 shadow-xl hover:scale-[1.02] transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-white/60">Courses</p>
                <h2 class="text-4xl font-bold mt-3 text-white"><?= $total_courses ?></h2>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-blue-500/20 text-xl">
                📚
            </div>
        </div>
    </div>

    <!-- Classes Card -->
    <div class="rounded-3xl p-8 bg-gradient-to-br from-[#1f1c2c] to-[#928dab] border border-white/10 shadow-xl hover:scale-[1.02] transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-white/60">Classes</p>
                <h2 class="text-4xl font-bold mt-3 text-white"><?= $total_classes ?></h2>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-cyan-500/20 text-xl">
                🏫
            </div>
        </div>
    </div>
    <div class="rounded-3xl p-8 bg-gradient-to-br from-[#141e30] to-[#243b55] border border-white/10 shadow-xl hover:scale-[1.01] transition col-span-1 md:col-span-2 lg:col-span-3">
    
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">Courses List</h2>
        <span class="text-sm text-white/40"><?= count($courses) ?> courses</span>
    </div>

    <div class="space-y-3">
        <?php foreach($courses as $course): ?>
            <div class="flex items-center justify-between bg-white/5 hover:bg-white/10 transition rounded-xl px-4 py-3 border border-white/5">
                
                <!-- Title -->
                <span class="text-white font-medium">
                    <?= $course['title'] ?>
                </span>

                <!-- Hours -->
                <span class="text-sm text-blue-400 font-mono">
                    <?= $course['total_hours'] ?> h
                </span>

            </div>
        <?php endforeach; ?>
    </div>

</div>
</div>