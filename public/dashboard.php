<?php

session_start();

if (empty($_SESSION['firstname'])) {
    echo $_SESSION['firstname'];
    header('Location: ../public/login.php');
    exit();
};

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
                    Users
                    <span class="ml-auto badge bg-blue-500/20 text-blue-300 border border-blue-500/30">2.4k</span>
                </div>

                <!-- Games -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    Games
                    <span class="ml-auto badge bg-purple-500/20 text-purple-300 border border-purple-500/30">48</span>
                </div>

                <!-- Orders -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Orders
                </div>

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
                    <div class="text-sm font-semibold text-white truncate"><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']   ?></div>
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
                        <span class="hidden sm:block text-sm font-semibold text-white"><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']   ?></span>
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
                <section>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                        <!-- Revenue -->
                        <div class="stat-card purple glass-card rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-mono text-white/40 uppercase tracking-widest mb-1">Revenue</p>
                                    <p class="text-3xl font-display font-bold text-white tracking-tight">$84.2K</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(123,94,167,0.2); border: 1px solid rgba(123,94,167,0.35);">
                                    <svg class="w-5 h-5" style="color:#a78bfa" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-mono" style="color:#a3e635">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M7 17l9.2-9.2M17 17V7H7" />
                                    </svg>
                                    +18.4% vs last mo
                                </div>
                                <!-- Mini sparkline SVG -->
                                <svg class="sparkline" viewBox="0 0 60 24" fill="none">
                                    <polyline points="0,20 10,16 20,18 30,10 40,8 50,4 60,2"
                                        stroke="#7b5ea7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <polyline points="0,20 10,16 20,18 30,10 40,8 50,4 60,2"
                                        stroke="url(#g1)" stroke-width="1.5" opacity="0.4" />
                                    <defs>
                                        <linearGradient id="g1" x1="0" y1="0" x2="60" y2="0" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#7b5ea7" />
                                            <stop offset="1" stop-color="#3b82f6" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="stat-card blue glass-card rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-mono text-white/40 uppercase tracking-widest mb-1">Active Users</p>
                                    <p class="text-3xl font-display font-bold text-white tracking-tight">24,391</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.35);">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-mono" style="color:#a3e635">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M7 17l9.2-9.2M17 17V7H7" />
                                    </svg>
                                    +6.1% this week
                                </div>
                                <svg class="sparkline" viewBox="0 0 60 24" fill="none">
                                    <polyline points="0,18 10,14 20,16 30,12 40,6 50,9 60,4"
                                        stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        <!-- Orders -->
                        <div class="stat-card cyan glass-card rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-mono text-white/40 uppercase tracking-widest mb-1">Orders</p>
                                    <p class="text-3xl font-display font-bold text-white tracking-tight">1,847</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(34,211,238,0.15); border: 1px solid rgba(34,211,238,0.3);">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-mono text-yellow-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 7l-9.2 9.2M7 7v10h10" />
                                    </svg>
                                    −2.3% today
                                </div>
                                <svg class="sparkline" viewBox="0 0 60 24" fill="none">
                                    <polyline points="0,4 10,8 20,6 30,12 40,10 50,14 60,18"
                                        stroke="#22d3ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        <!-- Games -->
                        <div class="stat-card lime glass-card rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-mono text-white/40 uppercase tracking-widest mb-1">Active Games</p>
                                    <p class="text-3xl font-display font-bold text-white tracking-tight">48</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(163,230,53,0.12); border: 1px solid rgba(163,230,53,0.3);">
                                    <svg class="w-5 h-5" style="color:#a3e635" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-mono" style="color:#a3e635">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M7 17l9.2-9.2M17 17V7H7" />
                                    </svg>
                                    3 launched today
                                </div>
                                <svg class="sparkline" viewBox="0 0 60 24" fill="none">
                                    <polyline points="0,20 10,18 20,14 30,16 40,10 50,6 60,4"
                                        stroke="#a3e635" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- END STAT CARDS -->

                <!-- ── BOTTOM GRID: Activity Table + Quick Panel ── -->
                <section class="grid grid-cols-1 xl:grid-cols-3 gap-5">

                    <!-- Activity Table -->
                    <div class="xl:col-span-2 glass-card rounded-2xl overflow-hidden">

                        <div class="px-6 py-4 flex items-center justify-between border-b border-border">
                            <div>
                                <h2 class="font-display font-bold text-white">Recent Activity</h2>
                                <p class="text-xs font-mono text-white/35 mt-0.5">Last 24 hours</p>
                            </div>
                            <button class="text-xs font-mono text-purple-400 hover:text-purple-300 transition-colors px-3 py-1.5 glass-card rounded-lg">
                                View all →
                            </button>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-border">
                                        <th class="text-left px-6 py-3 text-xs font-mono text-white/30 uppercase tracking-widest font-normal">User</th>
                                        <th class="text-left px-4 py-3 text-xs font-mono text-white/30 uppercase tracking-widest font-normal">Action</th>
                                        <th class="text-left px-4 py-3 text-xs font-mono text-white/30 uppercase tracking-widest font-normal hidden sm:table-cell">Game</th>
                                        <th class="text-left px-4 py-3 text-xs font-mono text-white/30 uppercase tracking-widest font-normal">Status</th>
                                        <th class="text-right px-6 py-3 text-xs font-mono text-white/30 uppercase tracking-widest font-normal">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#7b5ea7,#3b82f6)">JD</div>
                                                <span class="font-semibold text-white text-sm">Jake D.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Purchase</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">Neon Raiders</td>
                                        <td class="px-4 py-4"><span class="tag tag-success">Completed</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">2m ago</td>
                                    </tr>

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#22d3ee,#3b82f6)">SM</div>
                                                <span class="font-semibold text-white text-sm">Sara M.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Subscription</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">VoidRealm</td>
                                        <td class="px-4 py-4"><span class="tag tag-info">Processing</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">11m ago</td>
                                    </tr>

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#e879f9,#7b5ea7)">RP</div>
                                                <span class="font-semibold text-white text-sm">Ryo P.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Refund</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">CyberKnights</td>
                                        <td class="px-4 py-4"><span class="tag tag-danger">Failed</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">34m ago</td>
                                    </tr>

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#a3e635,#22d3ee)">AW</div>
                                                <span class="font-semibold text-white text-sm">Ana W.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Upgrade</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">StarPulse</td>
                                        <td class="px-4 py-4"><span class="tag tag-success">Completed</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">1h ago</td>
                                    </tr>

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#fbbf24,#f87171)">TC</div>
                                                <span class="font-semibold text-white text-sm">Tom C.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Purchase</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">GlitchWave</td>
                                        <td class="px-4 py-4"><span class="tag tag-warn">Pending</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">2h ago</td>
                                    </tr>

                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar text-xs" style="background:linear-gradient(135deg,#3b82f6,#7b5ea7)">LK</div>
                                                <span class="font-semibold text-white text-sm">Lena K.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-white/60 font-mono text-xs">Register</td>
                                        <td class="px-4 py-4 text-white/60 text-xs hidden sm:table-cell">—</td>
                                        <td class="px-4 py-4"><span class="tag tag-success">Completed</span></td>
                                        <td class="px-6 py-4 text-right font-mono text-xs text-white/35">3h ago</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Quick Info Panel -->
                    <div class="flex flex-col gap-4">

                        <!-- Top Games -->
                        <div class="glass-card rounded-2xl p-5 flex-1">
                            <h2 class="font-display font-bold text-white mb-1">Top Games</h2>
                            <p class="text-xs font-mono text-white/35 mb-4">by revenue this month</p>
                            <div class="space-y-3">

                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs text-white/30 w-4">01</span>
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-white font-semibold">Neon Raiders</span>
                                            <span class="text-xs font-mono text-white/50">$24.1K</span>
                                        </div>
                                        <div class="h-1 rounded-full bg-white/08 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:88%;background:linear-gradient(90deg,#7b5ea7,#3b82f6)"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs text-white/30 w-4">02</span>
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-white font-semibold">VoidRealm</span>
                                            <span class="text-xs font-mono text-white/50">$18.7K</span>
                                        </div>
                                        <div class="h-1 rounded-full bg-white/08 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:70%;background:linear-gradient(90deg,#22d3ee,#3b82f6)"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs text-white/30 w-4">03</span>
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-white font-semibold">CyberKnights</span>
                                            <span class="text-xs font-mono text-white/50">$11.3K</span>
                                        </div>
                                        <div class="h-1 rounded-full bg-white/08 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:46%;background:linear-gradient(90deg,#e879f9,#7b5ea7)"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs text-white/30 w-4">04</span>
                                    <div class="flex-1">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-white font-semibold">StarPulse</span>
                                            <span class="text-xs font-mono text-white/50">$9.8K</span>
                                        </div>
                                        <div class="h-1 rounded-full bg-white/08 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:38%;background:linear-gradient(90deg,#a3e635,#22d3ee)"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="glass-card rounded-2xl p-5">
                            <h2 class="font-display font-bold text-white mb-4">System Status</h2>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-white/60">API Gateway</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-lime-400" style="box-shadow:0 0 6px #a3e635"></div>
                                        <span class="tag tag-success text-xs">99.9%</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-white/60">Game Servers</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-lime-400" style="box-shadow:0 0 6px #a3e635"></div>
                                        <span class="tag tag-success text-xs">Online</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-white/60">Auth Service</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                                        <span class="tag tag-warn text-xs">Degraded</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-white/60">CDN</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-lime-400" style="box-shadow:0 0 6px #a3e635"></div>
                                        <span class="tag tag-success text-xs">Optimal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
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