

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

        <!-- END BOTTOM GRID -->

    </main>
    <!-- END MAIN -->

    <!-- ===================== FOOTER ===================== -->

    <!-- END FOOTER -->
