<?php
require '../scripts/connection.php';

/* total students */
$stmt = $conn->query("SELECT COUNT(*) as total FROM students");
$total_students = $stmt->fetch()['total'];

/* total courses */
$stmt = $conn->query("SELECT COUNT(*) as total FROM courses");
$total_courses = $stmt->fetch()['total'];

/* total classes */
$stmt = $conn->query("SELECT COUNT(*) as total FROM classes");
$total_classes = $stmt->fetch()['total'];
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

</div>