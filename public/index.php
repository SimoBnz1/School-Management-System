<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduSync | Modern Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@%5E2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #0B0F19; color: #ffffff; }
        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #4B5563; }
    </style>
</head>
<body class="antialiased overflow-x-hidden relative">

    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] -z-10 pointer-events-none"></div>

    <nav class="fixed w-full z-50 top-0 transition-all backdrop-blur-md bg-brand-dark/70 border-b border-brand-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center cursor-pointer hover:scale-105 transition-transform duration-300">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                        EduSync
                    </span>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#home" class="text-gray-300 hover:text-white transition-colors text-sm font-medium">Home</a>
                    <a href="#features" class="text-gray-300 hover:text-white transition-colors text-sm font-medium">Features</a>
                    <a href="#why-us" class="text-gray-300 hover:text-white transition-colors text-sm font-medium">Why Us</a>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="hidden sm:block text-gray-300 hover:text-white text-sm font-medium transition-colors">
                        <form action="./login.php" method="POST">
                            <input type="submit" value="Login" name="login1">
                        </form>
                    </button>
                    <button class="px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:-translate-y-0.5 transition-all duration-300">
                        <form action="./register.php" method="POST">
                            <input type="submit" value="Sign Up" name="register">
                        </form>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="pt-32 pb-20 lg:pt-48 lg:pb-32 px-4 flex flex-col items-center text-center max-w-7xl mx-auto relative">
        <div class="inline-flex items-center space-x-2 bg-white/5 border border-white/10 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm shadow-xl">
            <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
            <span class="text-sm font-medium text-gray-300">EduSync 2.0 is now live</span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 max-w-4xl leading-tight">
            Sync your learning. <br class="hidden md:block">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">
                Elevate your future.
            </span>
        </h1>
        
        <p class="text-lg md:text-xl text-gray-400 max-w-2xl mb-10 leading-relaxed">
            The ultimate platform to organize, track, and master your educational journey. Built for modern students and educators who demand excellence.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <button class="px-8 py-4 rounded-full text-base font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                Get Started for Free
            </button>
            <button class="px-8 py-4 rounded-full text-base font-semibold text-white bg-brand-card border border-brand-border hover:bg-white/10 hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto backdrop-blur-sm">
                View Live Demo
            </button>
        </div>

        <div class="mt-20 w-full max-w-5xl rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md p-2 shadow-2xl shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-500">
            <div class="w-full h-48 sm:h-80 md:h-[500px] rounded-xl bg-gradient-to-br from-gray-800/50 to-gray-900/50 flex flex-col items-center justify-center border border-white/5 relative overflow-hidden">
                <div class="absolute top-4 left-4 right-4 h-12 border-b border-white/5 flex items-center px-4 space-x-2">
                    <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                </div>
                <div class="absolute top-20 left-4 bottom-4 w-48 border-r border-white/5 hidden md:block"></div>
                <div class="absolute top-20 right-4 bottom-4 left-4 md:left-56 flex items-center justify-center">
                    <div class="text-gray-500 font-medium tracking-widest text-sm uppercase">Interactive Dashboard Preview</div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 relative bg-black/20 border-t border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Everything you need to succeed</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Powerful tools designed to reduce friction and keep you focused on what actually matters: learning and growing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group bg-brand-card border border-brand-border rounded-2xl p-8 hover:bg-white/5 hover:border-indigo-500/30 hover:-translate-y-2 hover:shadow-[0_10px_40px_rgba(99,102,241,0.15)] transition-all duration-300 backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center mb-6 text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-white">Smart Analytics</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Track your progress in real-time. Our AI-driven insights help you identify weak points and optimize your study schedule effortlessly.</p>
                </div>

                <div class="group bg-brand-card border border-brand-border rounded-2xl p-8 hover:bg-white/5 hover:border-purple-500/30 hover:-translate-y-2 hover:shadow-[0_10px_40px_rgba(168,85,247,0.15)] transition-all duration-300 backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center mb-6 text-purple-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-white">Collaborative Spaces</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Join forces with peers. Share notes, co-edit documents, and communicate instantly without ever leaving the platform.</p>
                </div>

                <div class="group bg-brand-card border border-brand-border rounded-2xl p-8 hover:bg-white/5 hover:border-pink-500/30 hover:-translate-y-2 hover:shadow-[0_10px_40px_rgba(236,72,153,0.15)] transition-all duration-300 backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center mb-6 text-pink-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-white">Bank-Grade Security</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Your data is yours. We employ end-to-end encryption to ensure that your materials and personal information remain completely private.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="why-us" class="py-24 px-4 relative">
        <div class="max-w-5xl mx-auto bg-gradient-to-br from-indigo-900/40 to-purple-900/40 border border-white/10 rounded-3xl p-8 md:p-16 backdrop-blur-md overflow-hidden relative">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-500/30 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Why choose EduSync?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1 mr-4">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-gray-300">Unified ecosystem replacing 5+ different apps.</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1 mr-4">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-gray-300">Lightning fast performance with offline capabilities.</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center mt-1 mr-4">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-gray-300">24/7 dedicated support and onboarding assistance.</p>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2 flex justify-center">
                    <div class="text-center p-8 bg-black/40 border border-white/10 rounded-2xl backdrop-blur-xl w-full">
                        <div class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400 mb-2">99.9%</div>
                        <div class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-6">Uptime Guarantee</div>
                        <button class="w-full px-6 py-3 rounded-xl text-sm font-semibold text-white bg-white/10 hover:bg-white/20 transition-colors border border-white/10">
                            Read our SLA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    include '../includes/footer.php';
    
    ?>

</body>
</html>