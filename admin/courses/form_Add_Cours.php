<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./style.css">
    
</head>

<body class="bg-[#0f172a] text-gray-100 font-sans">

<?php 
if (isset($_GET["err"]) && $_GET["err"] == "champVid") {
?>
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/50 text-red-400 text-sm flex items-center gap-3 shadow-lg backdrop-blur-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium text-center flex-1">Please fill in all required fields!</span>
        </div>
    </div>
<?php 
} 
?>

<main class="min-h-screen flex items-center justify-center p-6">
    <!-- Main Card -->
    <div class="glass-card w-full max-w-2xl rounded-2xl shadow-2xl p-8 relative overflow-hidden">
        
        <!-- Decoration Gradient -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <!-- Logo & Title -->
            <div class="flex flex-col items-center mb-8">
                <div class="mb-4">
                    <?php include '../../includes/logo.php'; ?>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-500">
                    Create New Class
                </h1>
                <p class="text-gray-400 text-sm mt-2">Enter details to register a new class</p>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-5" method="POST" action="../../scripts/add_Cours.php">
                <!-- First Name -->
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Title</label>
                    <input type="text" name="title" 
                        class="input-style w-full p-3 rounded-xl">
                </div>
                <!-- Last Name -->
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">description</label>
                    <input type="text" name="discription" 
                        class="input-style w-full p-3 rounded-xl">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">total_hour</label>
                    <input type="number" name="total_hour" 
                        class="input-style w-full p-3 rounded-xl">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Prof</label>
                    <input type="number" name="prof" 
                        class="input-style w-full p-3 rounded-xl">
                </div>
                <!-- Submit -->
                <div class="md:col-span-2 pt-4">
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-green-900/20 transform transition hover:-translate-y-0.5 active:scale-95">
                        Ajouter Cours
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

</body>
</html>