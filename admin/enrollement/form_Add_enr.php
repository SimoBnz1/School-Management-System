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
require '../../scripts/connection.php';
    $sql = "SELECT 
            students.id, 
            users.firstname, 
            users.lastname
        FROM students
        JOIN users ON students.user_id = users.id";;

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT id, title 
            FROM courses";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $courses =  $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
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

<main class="min-h-screen flex items-center justify-center p-6 bg-[#0f172a]">
    <!-- Main Card -->
    <div class="relative w-full max-w-2xl bg-gray-900/40 backdrop-blur-xl border border-white/10 rounded-[2rem] shadow-2xl p-10 overflow-hidden">
        
        <!-- Decoration Glows (bach may-bkatch dakchi k7el) -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px]"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px]"></div>

        <div class="relative z-10">
            <!-- Logo & Title -->
            <div class="flex flex-col items-center mb-10 text-center">
                <div class="mb-6 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <?php include '../../includes/logo.php'; ?>
                </div>
                <h1 class="text-4xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-br from-white via-emerald-400 to-green-500">
                    New Enrollment
                </h1>
                <p class="text-gray-400 text-xs mt-3 uppercase tracking-[0.3em] font-bold opacity-60">Register student to course</p>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-6" method="POST" action="../../scripts/add_enr.php">

                <!-- Student Selection -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest ml-2">Choose Student</label>
                    <div class="relative group">
                        <select name="student_id" class="w-full bg-white/5 border border-white/10 text-white p-4 rounded-2xl appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all">
                              <option value="" class="bg-gray-900 text-gray-400 italic">-- Select Student --</option>
                              <?php foreach ($students as $s): ?>
                                <option value="<?= $s['id']; ?>" class="bg-gray-900 text-white"><?= $s['firstname'] . ' ' . $s['lastname']; ?></option>
                              <?php endforeach; ?>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Course Selection -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest ml-2">Choose Course</label>
                    <div class="relative group">
                        <select name="course_id" class="w-full bg-white/5 border border-white/10 text-white p-4 rounded-2xl appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all">
                          <option value="" class="bg-gray-900 text-gray-400 italic">-- Select Course --</option>
                              <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['id']; ?>" class="bg-gray-900 text-white"><?= $c['title']; ?></option>
                              <?php endforeach; ?>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest ml-2">Status</label>
                    <div class="relative group">
                        <select name="status" class="w-full bg-white/5 border border-white/10 text-white p-4 rounded-2xl appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition-all">
                            <option value="" class="bg-gray-900 text-gray-500 italic">-- Select Status --</option>
                            <option value="Actif" class="bg-gray-900 text-white">Actif</option>
                            <option value="Terminé" class="bg-gray-900 text-white">Terminé</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 pt-6">
                    <button type="submit" 
                        class="group relative w-full bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-[#0f172a] py-4 rounded-2xl font-black uppercase tracking-widest shadow-[0_10px_30px_-10px_rgba(16,185,129,0.5)] transition-all transform hover:-translate-y-1 active:scale-95">
                        <span class="flex items-center justify-center gap-2">
                            Create Enrollment
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<?php if (file_exists('../includes/footer.php')) include '../includes/footer.php'; ?>

</body>
</html>