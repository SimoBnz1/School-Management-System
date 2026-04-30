<?php
if(isset($_GET["id"])){
$user_id=$_GET["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./style.css">
   
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen">

    <!-- Modal Container (Hiyed 'hidden' bach t-choufha) -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-50 p-4">

        <div class="glass-container relative rounded-[2rem] p-10 w-full max-w-md shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden">
            
            <!-- Glow effect bach n-7eydou dik l-khlawiya -->
            <div class="absolute -top-20 -left-20 w-40 h-40 bg-emerald-500/20 rounded-full blur-[60px]"></div>
            <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-blue-500/20 rounded-full blur-[60px]"></div>

            <div class="relative z-10">
                <h2 class="text-3xl font-black text-white mb-2 tracking-tight text-center">
                    Edit <span class="text-emerald-400">User</span>
                </h2>
                <p class="text-gray-400 text-[10px] text-center uppercase tracking-[0.3em] mb-8 font-bold opacity-60">Update account info</p>

                <form method="POST" action="./update_user.php" class="space-y-5">
                    <input type="hidden" name="id" value="<?= $user_id?>" >

                    <!-- Input Name -->
                    <div>
                        <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-2 ml-2">Full Name</label>
                        <input type="text" name="firstname" id="edit_name"
                            class="input-glass w-full p-4 rounded-2xl text-sm transition-all duration-300"
                            placeholder="John Doe">
                    </div>

                    <!-- Input Email -->
                    <div>
                        <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-2 ml-2">Email Address</label>
                        <input type="email" name="email" id="edit_email"
                            class="input-glass w-full p-4 rounded-2xl text-sm transition-all duration-300"
                            placeholder="john@example.com">
                    </div>

                    <!-- Select Role -->
                    <div class="relative">
                        <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-2 ml-2">Role</label>
                        <select name="role_id" id="edit_role"
                            class="input-glass w-full p-4 rounded-2xl appearance-none cursor-pointer text-sm">
                            <option value="2" class="bg-[#1e293b]">Teacher</option>
                            <option value="3" class="bg-[#1e293b]">Student</option>
                        </select>
                        <div class="absolute right-4 top-[38px] text-emerald-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-6">
                        <button type="button" onclick="closeEditModal()" 
                            class="text-gray-400 hover:text-white font-bold text-xs uppercase tracking-widest transition-all px-4 py-2">
                            Cancel
                        </button>
                        
                        <button type="submit" 
                            class="bg-emerald-500 hover:bg-emerald-400 text-[#0f172a] font-black text-xs uppercase tracking-[0.2em] px-8 py-4 rounded-xl shadow-[0_10px_20px_rgba(16,185,129,0.3)] transition-all transform hover:-translate-y-1 active:scale-95">
                            Update Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
<?php
}
?>