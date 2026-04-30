<?php
if(isset($_GET["id"])){
$class_id=$_GET["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ClassRoom Number</title>
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
                    Edit <span class="text-emerald-400">Class</span>
                </h2>
                <p class="text-gray-400 text-[10px] text-center uppercase tracking-[0.3em] mb-8 font-bold opacity-60">Update ClassRoom info</p>

                <form method="POST" action="./update_classes.php" class="space-y-5">
                    <input type="hidden" name="id" value="<?= $class_id?>" >

                    <!-- Input Name -->
                    <div>
                        <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-2 ml-2">Name Class</label>
                        <input type="text" name="name" id="edit_name"
                            class="input-glass w-full p-4 rounded-2xl text-sm transition-all duration-300"
                           >
                    </div>

                    <!-- classroom_number  -->
                    <div>
                        <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-2 ml-2">ClassRoom Number</label>
                        <input type="text" name="classroom" id="classroom_id"
                            class="input-glass w-full p-4 rounded-2xl text-sm transition-all duration-300"
                           >
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