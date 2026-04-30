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
                    <span class="text-white font-display font-800 text-lg tracking-tight leading-none">EduSync</span>
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
                    <a href="/mywebsite/admin/dashboard.php">Dashboard</a>
                </div>

                <!-- Users -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <a href="/mywebsite/admin/users/users.php">Users</a>
                    <span class="ml-auto badge bg-blue-500/20 text-blue-300 border border-blue-500/30">2.4k</span>
                </div>

                <!-- Games -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    <a href="/mywebsite/admin/classes/classes.php">Classes</a>
                    <span class="ml-auto badge bg-purple-500/20 text-purple-300 border border-purple-500/30">48</span>
                </div>

                <!-- Orders -->
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <a href="/mywebsite/admin/courses/courses.php">Courses</a>
                </div>
                <div class="nav-item">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <a href="/mywebsite/admin/enrollement/enrollments.php">Enrollments</a>
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