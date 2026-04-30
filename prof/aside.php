
<?php
session_start();
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
        <aside id="sidebar" class="w-64 flex-shrink-0 flex flex-col bg-panel border-r border-border p-5 gap-6 overflow-y-auto">

            <!-- Logo -->
            <div class="flex items-center gap-3 px-1 pt-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                    style="background: linear-gradient(135deg,#7b5ea7,#3b82f6); box-shadow: 0 0 16px rgba(123,94,167,0.5);">
                    <!-- Lightning bolt icon -->
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                    </svg>
                </div>
                <div>
                    <span class="text-white font-display font-800 text-lg tracking-tight leading-none">NeonCore</span>
                    <div class="font-mono text-xs text-purple-400 opacity-70 mt-0.5">v2.4.1</div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col gap-1 flex-1">
                <div class="text-xs font-mono text-white/25 px-3 pb-2 pt-1 uppercase tracking-widest">Main</div>

                <!-- Dashboard (active) -->
                <div class="nav-item active">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </div>

                <!-- Users -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <a href="?page=students">students</a>
                    
                    <span class="ml-auto badge bg-blue-500/20 text-blue-300 border border-blue-500/30">2.4k</span>
                </div>

                <!-- Games -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    
                    <a href="?page=classes">classes</a>
                    <span class="ml-auto badge bg-purple-500/20 text-purple-300 border border-purple-500/30">48</span>
                </div>

                <!-- Orders -->
                

                <div class="text-xs font-mono text-white/25 px-3 pb-2 pt-4 uppercase tracking-widest">System</div>

                <!-- Settings -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </div>
            </nav>

            <!-- Sidebar footer: user mini profile -->
            <div class="glass-card rounded-2xl p-4 flex items-center gap-3">
                <div class="avatar" style="background: linear-gradient(135deg,#7b5ea7,#3b82f6);">AK</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-white truncate"></div>
                    <div class="text-xs text-white/40 font-mono truncate">admin</div>
                </div>
                <div class="w-2 h-2 rounded-full bg-lime-400" style="box-shadow: 0 0 6px #a3e635;"></div>
            </div>

        </aside>
        <!-- END SIDEBAR -->

        <!-- ===================== MAIN CONTENT WRAPPER ===================== -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- ===================== HEADER / NAVBAR ===================== -->
            <header class="flex items-center justify-between px-6 py-4 border-b border-border bg-panel/60 backdrop-blur-lg flex-shrink-0">

                <!-- Left: hamburger + page title -->
                <div class="flex items-center gap-4">
                    <!-- Hamburger (mobile) -->
                    <button onclick="openSidebar()" class="lg:hidden p-2 rounded-lg text-white/50 hover:text-white hover:bg-white/08 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-display font-bold text-white leading-none tracking-tight">Overview</h1>
                        <p class="text-xs font-mono text-white/35 mt-0.5">April 24, 2026 &nbsp;·&nbsp; Live data</p>
                    </div>
                </div>

                <!-- Right: search + notifications + user -->
                <div class="flex items-center gap-3">

                    <!-- Search bar (hidden on small) -->
                    <div class="hidden md:flex items-center gap-2 glass-card rounded-xl px-4 py-2 text-sm text-white/40 font-mono w-52 cursor-text hover:border-purple-500/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search...</span>
                        <span class="ml-auto text-xs bg-white/08 px-1.5 py-0.5 rounded">⌘K</span>
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2.5 glass-card rounded-xl text-white/50 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-magenta rounded-full" style="box-shadow: 0 0 6px #e879f9;"></span>
                    </button>

                    <!-- User pill -->
                    <div class="flex items-center gap-2.5 glass-card rounded-xl px-3 py-2 cursor-pointer hover:border-purple-500/30 transition-all">
                        <div class="avatar w-7 h-7 text-xs" style="background: linear-gradient(135deg,#7b5ea7,#3b82f6);">AK</div>
                        <span class="hidden sm:block text-sm font-semibold text-white"></span>
                        <svg class="w-4 h-4 text-white/30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Logout -->
                    <button class="logout-btn flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white/80 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <form action="../scripts/logout.php" method="post">
                            <input type="submit" value="Chekout" name="logout">
                        </form>
                    </button>

                </div>
            </header>
            <!-- END HEADER -->

            <!-- ===================== MAIN DASHBOARD ===================== -->
            <main class="flex-1 overflow-y-auto px-6 py-6 space-y-6">

                <!-- ── STAT CARDS ── -->
              <?php
          $page = $_GET['page'] ?? 'dashboard';

          if ($page == 'dashboard') {
              require_once __DIR__ . '/dashboard.php';
          }

          elseif ($page == 'classes') {
              require_once __DIR__ . '/class_details.php';
          }

          elseif ($page == 'courses') {
              require_once __DIR__ . '/courses.php';
          }

          else {
              echo "<h2>الصفحة غير موجودة</h2>";
          }
          ?>
                <!-- END BOTTOM GRID -->
                 




            </main>
            <!-- END MAIN -->

            <!-- ===================== FOOTER ===================== -->
            <footer class="flex items-center justify-between px-6 py-3 border-t border-border bg-panel/60 backdrop-blur-lg flex-shrink-0">
                <p class="text-xs font-mono text-white/25">© 2026 NeonCore Inc. — All rights reserved.</p>
                <div class="flex items-center gap-4 text-xs font-mono text-white/25">
                    <a href="#" class="hover:text-white/60 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white/60 transition-colors">Terms</a>
                    <a href="#" class="hover:text-white/60 transition-colors">Support</a>
                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime-400 inline-block" style="box-shadow:0 0 5px #a3e635"></span>
                        All systems operational
                    </span>
                </div>
            </footer>
            <!-- END FOOTER -->

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