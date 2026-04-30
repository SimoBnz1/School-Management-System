<?php

session_start();
if ($_SESSION['role'] != "1") {
    echo $_SESSION['role'];
    header('Location: ../public/login.php');
    exit();
};

?>
<?php
require '../scripts/connection.php';
$sql = "SELECT COUNT(*)  FROM users WHERE role_id=3";

$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchColumn();


$sql = "SELECT COUNT(*)  FROM courses ";

$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchColumn();



$sql = "SELECT COUNT(*)  FROM classes ";

$stmt = $conn->prepare($sql);
$stmt->execute();
$classes = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NeonCore Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@%5E2/dist/tailwind.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Syne', sans-serif;
            background-color: #07070f;
            background-image:
                radial-gradient(ellipse 80% 60% at 10% 0%, rgba(123, 94, 167, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 90% 100%, rgba(59, 130, 246, 0.1) 0%, transparent 60%);
            min-height: 100vh;
        }

        /* Animated grid background */
        .grid-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image:
                linear-gradient(rgba(123, 94, 167, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(123, 94, 167, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.035);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            transition: all 0.3s cubic-bezier(.4, 0, .2, 1);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.13);
            transform: translateY(-2px);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.purple::before {
            background: radial-gradient(circle at 0% 0%, rgba(123, 94, 167, 0.18) 0%, transparent 60%);
        }

        .stat-card.blue::before {
            background: radial-gradient(circle at 0% 0%, rgba(59, 130, 246, 0.18) 0%, transparent 60%);
        }

        .stat-card.cyan::before {
            background: radial-gradient(circle at 0% 0%, rgba(34, 211, 238, 0.18) 0%, transparent 60%);
        }

        .stat-card.lime::before {
            background: radial-gradient(circle at 0% 0%, rgba(163, 230, 53, 0.18) 0%, transparent 60%);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-item.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(123, 94, 167, 0.3), rgba(59, 130, 246, 0.15));
            border: 1px solid rgba(123, 94, 167, 0.35);
            box-shadow: 0 0 20px rgba(123, 94, 167, 0.15);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, #7b5ea7, #3b82f6);
        }

        .badge {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .logout-btn {
            background: linear-gradient(135deg, rgba(123, 94, 167, 0.25), rgba(59, 130, 246, 0.2));
            border: 1px solid rgba(123, 94, 167, 0.4);
            transition: all 0.25s ease;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, rgba(123, 94, 167, 0.45), rgba(59, 130, 246, 0.35));
            box-shadow: 0 0 20px rgba(123, 94, 167, 0.3);
            transform: scale(1.03);
        }

        /* Sidebar toggle */
        #sidebar {
            transition: transform 0.3s ease;
        }

        #overlay {
            display: none;
        }

        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #overlay.show {
                display: block;
            }
        }

        .table-row {
            transition: background 0.2s ease;
        }

        .table-row:hover {
            background: rgba(123, 94, 167, 0.08);
        }

        .sparkline {
            width: 60px;
            height: 24px;
            display: inline-block;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(123, 94, 167, 0.4);
            border-radius: 2px;
        }

        .tag {
            font-family: 'DM Mono', monospace;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 999px;
            font-weight: 500;
        }

        .tag-success {
            background: rgba(163, 230, 53, 0.12);
            color: #a3e635;
            border: 1px solid rgba(163, 230, 53, 0.25);
        }

        .tag-warn {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.25);
        }

        .tag-info {
            background: rgba(34, 211, 238, 0.12);
            color: #22d3ee;
            border: 1px solid rgba(34, 211, 238, 0.25);
        }

        .tag-danger {
            background: rgba(248, 113, 113, 0.12);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.25);
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'DM Mono', monospace;
        }
    </style>
</head>

<body class="text-white overflow-x-hidden">

    <!-- Background grid -->
    <div class="grid-bg"></div>

    <!-- ===================== OVERLAY (mobile) ===================== -->
    <div id="overlay" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm" onclick="closeSidebar()"></div>

    <div class="relative z-10 flex h-screen overflow-hidden">

        <!-- ===================== SIDEBAR ===================== -->

        <?php
        include('sideBar.php')
        ?>

        <!-- ===================== MAIN CONTENT WRAPPER ===================== -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php
            include('navBar.php');

            ?>

                <div class="min-h-screen bg-[#080d17] p-4 md:p-10 font-sans text-gray-200">

                    <!-- Header Section -->
                    <div class="max-w-7xl mx-auto mb-10 flex justify-between items-end">
                        <div>
                            <h1 class="text-4xl font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white via-emerald-400 to-green-500">
                                System Overview
                            </h1>
                            <p class="text-gray-500 text-xs uppercase tracking-[0.4em] mt-2 font-bold">Real-time Analytics Engine</p>
                        </div>
                        <div class="hidden md:block">
                            <span class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-xs font-bold">
                                ● Live System Status
                            </span>
                        </div>
                    </div>

                    <!-- Main Bento Grid -->
                    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">

                        <!-- 1. Total Students (Stat Card) -->
                        <div class="relative overflow-hidden group bg-gray-900/40 backdrop-blur-md border border-white/5 p-6 rounded-[2.5rem] shadow-2xl transition-all hover:border-emerald-500/30">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                            <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Total Students</p>
                            <div class="mt-4 flex items-baseline gap-2">
                                <h2 class="text-5xl font-black text-white italic"><?= $users ?></h2>
                                <span class="text-emerald-400 text-xs font-bold">+12%</span>
                            </div>
                            <div class="mt-6 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-green-300 h-full w-[75%] shadow-[0_0_12px_rgba(16,185,129,0.5)]"></div>
                            </div>
                        </div>

                        <!-- 2. Total Professors (Stat Card) -->
                        <div class="relative overflow-hidden group bg-gray-900/40 backdrop-blur-md border border-white/5 p-6 rounded-[2.5rem] shadow-2xl transition-all hover:border-blue-500/30">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                            <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Active Professors</p>
                            <div class="mt-4 flex items-baseline gap-2">
                                <h2 class="text-5xl font-black text-white italic"><?= $courses ?></h2>
                                <span class="text-blue-400 text-xs font-bold">Active</span>
                            </div>
                            <div class="mt-6 flex -space-x-3 overflow-hidden">
                                <!-- Mini Avatars for Visual Flair -->
                                <div class="w-8 h-8 rounded-full border-2 border-[#080d17] bg-gray-700"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-[#080d17] bg-gray-600"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-[#080d17] bg-emerald-500 flex items-center justify-center text-[10px] font-bold">+5</div>
                            </div>
                        </div>

                        <!-- 3. Total Classes (Stat Card) -->
                        <div class="relative overflow-hidden group bg-gray-900/40 backdrop-blur-md border border-white/5 p-6 rounded-[2.5rem] shadow-2xl transition-all hover:border-purple-500/30">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                            <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Active Courses</p>
                            <div class="mt-4 flex items-baseline gap-2">
                                <h2 class="text-5xl font-black text-white italic"><?= $classes ?></h2>
                                <span class="text-purple-400 text-xs font-bold">Live</span>
                            </div>
                            <p class="mt-4 text-gray-500 text-[10px] leading-relaxed">Engaged in 12 different categories across all departments.</p>
                        </div>

                        <!-- 4. Quick Action Card -->
                        <div class="bg-gradient-to-br from-emerald-500 to-green-700 p-1 rounded-[2.5rem] shadow-lg shadow-emerald-500/10">
                            <button class="w-full h-full bg-[#080d17] rounded-[2.4rem] flex flex-col items-center justify-center gap-2 hover:bg-transparent transition-all group">
                                <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center group-hover:bg-white/20 transition-all">
                                    <svg class="w-6 h-6 text-emerald-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest text-emerald-400 group-hover:text-white">Add New Data</span>
                            </button>
                        </div>

                        <!-- 5. Large Analytics Card (Spans 3 columns) -->
                        <div class="md:col-span-3 relative overflow-hidden bg-gray-900/40 backdrop-blur-md border border-white/5 p-8 rounded-[3rem] shadow-2xl">
                            <div class="flex justify-between items-center mb-10">
                                <h3 class="text-xl font-bold italic tracking-tight">Enrollment Velocity</h3>
                                <select class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold outline-none">
                                    <option class="bg-gray-900">Last 7 Days</option>
                                    <option class="bg-gray-900">Last 30 Days</option>
                                </select>
                            </div>
                            <!-- Mockup for a Chart -->
                            <div class="h-48 w-full flex items-end gap-2 md:gap-4 px-2">
                                <div class="flex-1 bg-white/5 h-20 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                                <div class="flex-1 bg-white/5 h-32 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                                <div class="flex-1 bg-white/5 h-24 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                                <div class="flex-1 bg-white/10 h-40 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer border-t-2 border-emerald-400"></div>
                                <div class="flex-1 bg-white/5 h-28 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                                <div class="flex-1 bg-white/5 h-36 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                                <div class="flex-1 bg-white/5 h-16 rounded-t-xl hover:bg-emerald-500/40 transition-all cursor-pointer"></div>
                            </div>
                        </div>

                        <!-- 6. Notifications / Feed -->
                        <div class="md:col-span-1 bg-gray-900/40 backdrop-blur-md border border-white/5 p-8 rounded-[3rem]">
                            <h3 class="text-sm font-black uppercase tracking-widest text-gray-500 mb-6">Activity</h3>
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="w-2 h-2 mt-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,1)]"></div>
                                    <div>
                                        <p class="text-xs font-bold">New Student Join</p>
                                        <p class="text-[10px] text-gray-500">2 minutes ago</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-2 h-2 mt-1.5 rounded-full bg-blue-500"></div>
                                    <div>
                                        <p class="text-xs font-bold">Professor Assigned</p>
                                        <p class="text-[10px] text-gray-500">1 hour ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

        </div>



        <!-- END MAIN CONTENT WRAPPER -->
    </div>

    <!-- ===================== JS: Sidebar toggle ===================== -->
    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('overlay').classList.add('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        }
    </script>

</body>

</html>