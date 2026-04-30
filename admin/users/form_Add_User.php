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
    $sql = "SELECT * 
            FROM classes";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $classes =  $stmt->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <?php
    if (isset($_GET["err"]) && $_GET["err"] == "champVid") {
    ?>
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/50 text-red-400 text-sm flex items-center gap-3 shadow-lg backdrop-blur-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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
                        Create New Account
                    </h1>
                    <p class="text-gray-400 text-sm mt-2">Enter details to register a new user</p>
                </div>

                <form class="grid grid-cols-1 md:grid-cols-2 gap-5" method="POST" action="../../scripts/add_User.php">

                    <!-- First Name -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">First Name</label>
                        <input type="text" name="firstName" placeholder="John"
                            class="input-style w-full p-3 rounded-xl">
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Last Name</label>
                        <input type="text" name="lastName" placeholder="Doe"
                            class="input-style w-full p-3 rounded-xl">
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Email Address</label>
                        <input type="email" name="email" placeholder="john@example.com"
                            class="input-style w-full p-3 rounded-xl">
                    </div>

                    <!-- Role -->
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">User Role</label>
                        <select name="role" id="role" class="input-style w-full p-3 rounded-xl appearance-none cursor-pointer">
                            <option value="" class="bg-gray-900 text-gray-500">-- Select Role --</option>
                            <option value="1" class="bg-gray-900">Admin</option>
                            <option value="2" class="bg-gray-900">Teacher</option>
                            <option value="3" class="bg-gray-900">Student</option>
                        </select>
                    </div>
                    <div id="studentFields" class="hidden space-y-4 pt-4 border-t">

                        <div>
                            <label class="block text-sm font-semibold text-blue-900 mb-1">
                                Date of birth
                            </label>
                            <input type="date" name="dateofbirth"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1">
                            Student number
                        </label>
                        <input type="text" name="student_number"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-blue-900 mb-1">
                            Class
                        </label>
                        <select name="id_classe"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Select class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>">
                                    <?php echo htmlspecialchars($class['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>





                    </div>


                    

            </div>

            <!-- Password -->
            <div class="space-y-1 relative">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="input-style w-full p-3 rounded-xl">
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1 relative">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Confirm Password</label>
                <input type="password" name="confirmPassword" placeholder="••••••••"
                    class="input-style w-full p-3 rounded-xl">

                <?php if (isset($_GET["err"]) && $_GET["err"] == "passM"): ?>
                    <span class="absolute -bottom-5 left-1 text-red-400 text-[10px] font-bold uppercase tracking-tighter">
                        Passwords do not match!
                    </span>
                <?php endif; ?>
            </div>

            <!-- Terms -->
            <div class="md:col-span-2 flex items-center gap-3 mt-2">
                <input type="checkbox" id="terms" class="w-5 h-5 rounded border-gray-700 bg-gray-800 text-emerald-500 focus:ring-emerald-500/20" required>
                <label for="terms" class="text-sm text-gray-400">
                    I accept the <a href="#" class="text-emerald-400 hover:underline">Terms and Conditions</a>
                </label>
            </div>

            <!-- Submit -->
            <div class="md:col-span-2 pt-4">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-green-900/20 transform transition hover:-translate-y-0.5 active:scale-95">
                    Create Account
                </button>
            </div>

            <!-- Footer Link -->
            <div class="md:col-span-2 text-center text-sm text-gray-500 pt-2">
                Already have an account?
                <a href="../public/login.php" class="text-emerald-400 font-semibold hover:text-emerald-300 hover:underline ml-1 transition-colors">Login here</a>
            </div>

            </form>
        </div>
        </div>
    </main>

    <?php if (file_exists('../includes/footer.php')) include '../includes/footer.php'; ?>
    <script>
        const roleSelect = document.getElementById("role");
        const studentFields = document.getElementById("studentFields");

        roleSelect.addEventListener("change", function() {
            if (this.value === "3") {
                studentFields.classList.remove("hidden");
            } else {
                studentFields.classList.add("hidden");
            }
        });
    </script>
</body>

</html>