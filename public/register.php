<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<?php 
if (isset($_GET["err"])) {
    if ($_GET["err"] == "champVid") {
?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert"> 
        <span class="font-medium">Un champ vide !!!!!!!</span>
    </div>
<?php 
    }
} 
?>

<main>
    <section class="bg-blue-50 dark:bg-blue-900 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow dark:bg-blue-800 dark:border dark:border-blue-700 p-6">

            <div class="flex items-center">
                <?php include '../includes/logo.php'; ?>
            </div>

            <h1 class="text-xl font-bold text-blue-900 md:text-2xl dark:text-white mb-6 text-center">
                Create an account
            </h1>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="../scripts/register_process.php">

                <div>
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">First Name</label>
                    <input type="text" name="firstName"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">Last Name</label>
                    <input type="text" name="lastName"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">
                </div>

          
                <div class="md:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">Email</label>
                    <input type="email" name="email"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">Role</label>

                    <select name="role"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">
                        
                        <option value="">-- Select Role --</option>
                        <option value="3">Student</option>

                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">Password</label>
                    <input type="password" name="password"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-blue-900 dark:text-white">Confirm Password</label>
                    <input type="password" name="confirmPassword"
                        class="w-full p-2.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-blue-700 dark:border-blue-600 dark:text-white">

                    <?php
                    if (isset($_GET["err"])) {
                        if ($_GET["err"] == "passM") {
                    ?>
                        <span class="text-red-600 text-sm font-medium">
                            Password doesn't match
                        </span>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="md:col-span-2 flex items-center">
                    <input type="checkbox" class="mr-2 w-4 h-4" required>
                    <label class="text-sm text-blue-500 dark:text-blue-300">
                        I accept the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium">
                        Create an account
                    </button>
                </div>

                <div class="md:col-span-2 text-center text-sm text-blue-500 dark:text-blue-400">
                    Already have an account?
                    <a href="../public/login.php" class="text-blue-600 hover:underline">Login here</a>
                </div>

            </form>

        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>