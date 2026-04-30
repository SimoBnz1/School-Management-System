<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create New Class</title>
</head>

<body class="bg-[#020617] text-gray-100 font-sans tracking-tight">
<?php 
require '../../scripts/connection.php';
    $sql = "SELECT * 
            FROM users WHERE role_id=2";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $prof =  $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php if (isset($_GET["err"]) && $_GET["err"] == "champVid"): ?>
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-3 backdrop-blur-xl shadow-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">Missing Info! Fill everything, please.</span>
        </div>
    </div>
<?php endif; ?>

<main class="min-h-screen flex items-center justify-center p-6 bg-[#020617]">
    
    <!-- Glow behind the card -->
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px]"></div>

    <div class="relative w-full max-w-2xl bg-[#0f172a]/40 backdrop-blur-2xl border border-white/5 rounded-[3rem] p-10 md:p-16 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block p-4 bg-emerald-500/10 rounded-3xl mb-6 border border-emerald-500/20">
                <?php include '../../includes/logo.php'; ?>
            </div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter uppercase">
                Add <span class="text-emerald-500 underline decoration-emerald-500/30 underline-offset-8">Course</span>
            </h1>
        </div>

        <form class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10" method="POST" action="../../scripts/add_Cours.php">
            
            <!-- Input Title -->
            <div class="relative group">
                <input type="text" name="title" required
                    class="peer w-full bg-transparent border-b-2 border-slate-700 py-2 text-white outline-none focus:border-emerald-500 transition-colors placeholder-transparent"
                    placeholder="Course Title">
                <label class="absolute left-0 -top-5 text-[10px] font-black uppercase tracking-widest text-emerald-500/60 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-5 peer-focus:text-emerald-500">
                    Course Title
                </label>
            </div>

            <!-- Input Description -->
            <div class="relative group">
                <input type="text" name="discription" required
                    class="peer w-full bg-transparent border-b-2 border-slate-700 py-2 text-white outline-none focus:border-emerald-500 transition-colors placeholder-transparent"
                    placeholder="Description">
                <label class="absolute left-0 -top-5 text-[10px] font-black uppercase tracking-widest text-emerald-500/60 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-5 peer-focus:text-emerald-500">
                    Description
                </label>
            </div>

            <!-- Input Hours -->
            <div class="relative group">
                <input type="number" name="total_hour" required
                    class="peer w-full bg-transparent border-b-2 border-slate-700 py-2 text-white outline-none focus:border-emerald-500 transition-colors placeholder-transparent"
                    placeholder="Hours">
                <label class="absolute left-0 -top-5 text-[10px] font-black uppercase tracking-widest text-emerald-500/60 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-5 peer-focus:text-emerald-500">
                    Total Hours
                </label>
            </div>

            <!-- Input Prof -->
            <div class="relative group">
                
                 <select name="prof"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Select class</option>
                            <?php foreach ($prof as $p): ?>
                                <option value="<?php echo $p['id']; ?>">
                                    <?php echo htmlspecialchars($p['firstname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-2 pt-6">
                <button type="submit" 
                    class="w-full bg-white text-[#020617] py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-400 transition-all shadow-[0_0_30px_rgba(255,255,255,0.1)] active:scale-95">
                    Save New Course
                </button>
            </div>

        </form>
    </div>
</main>

</body>
</html>