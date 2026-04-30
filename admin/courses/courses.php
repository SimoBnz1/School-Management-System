<?php
require __DIR__ . '/../../scripts/connection.php';

session_start();
if ($_SESSION['role'] != "1") {
    echo $_SESSION['role'];
    header('Location: ../public/login.php');
    exit();
};
$sql = "SELECT * FROM courses ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NeonCore Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@%5E2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body class="text-white overflow-x-hidden">

    <!-- Background grid -->
    <div class="grid-bg"></div>

    <!-- ===================== OVERLAY (mobile) ===================== -->
    <div id="overlay" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm" onclick="closeSidebar()"></div>

    <div class="relative z-10 flex h-screen overflow-hidden">

        
<?php include(__DIR__ . '/../sideBar.php') ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- NAVBAR -->
           <?php include(__DIR__ . '/../navBar.php') ?>

            <!-- CONTENT -->
            <div class="p-6 mt-4 mb-18">

                <div class="glass-card p-6 rounded-xl">
                    <!-- Header m3a Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Users Cours</h2>

                        <a href="./form_Add_Cours.php"
                            class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Cour
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-gray-400 border-b border-gray-700">
                                    <th class="py-2">Title</th>
                                    <th class="py-2">description</th>
                                    <th class="py-2">totale_hours</th>
                                    <th class="py-2">prof</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($courses as $cr): ?>
                                    <tr class="table-row border-b border-gray-800">

                                        <td class="py-3"><?= $cr['title'] ?></td>
                                        <td class="py-3"><?= $cr['description'] ?></td>
                                        <td class="py-3"><?= $cr['total_hours'] ?></td>
                                        <td class="py-3"><?= $cr['user_id'] ?></td>

                                        

                                        <!-- ACTIONS -->
                                        <td class="py-3 text-right">
                                            <div class="flex justify-end gap-3">

                                                <!-- EDIT -->
                                                <a href="./form_Edit_Cours.php?id=<?= $cr['id'] ?>">
                                                    <button
                                                    class="text-blue-400 hover:text-blue-300">
                                                    Edit
                                                </button>
                                                </a>

                                                <!-- DELETE -->
                                                <a href="delet_cours.php?id=<?= $cr['id'] ?>"
                                                    onclick="return confirm('Delete this Cours?')"
                                                    class="text-red-400 hover:text-red-300 transition">
                                                    <button>Delete</button>
                                                </a>

                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- EDIT MODAL -->
            

        </div>

    </div>

    </div>

    </div>



    </div>

    </div>



    <!-- ===================== JS: Sidebar toggle ===================== -->
    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('overlay').classList.add('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        }

    </script>

</body>

</html>