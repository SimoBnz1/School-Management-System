<?php
require '../../scripts/connection.php';

session_start();
if ($_SESSION['role'] != "1") {
    echo $_SESSION['role'];
    header('Location: ../public/login.php');
    exit();
};
$stmt = $conn->query("
 SELECT 
        enrollments.id,
        users.firstname AS student,
        courses.title AS course,
        enrollments.status
    FROM enrollments 
    JOIN students ON enrollments.student_id = students.id
    JOIN users ON  students.user_id=users.id
    JOIN courses ON enrollments.course_id = courses.id 
");
$enrollments = $stmt->fetchAll();   
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
                        <h2 class="text-xl font-bold">Enrollments List</h2>

                        <a href="./form_Add_enr.php"
                            class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Enrollments
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm mt-6">
                            <thead>
                                <tr class="text-gray-400 border-b">
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($enrollments as $e): ?>
                                    <tr class="border-b border-gray-800">
                                        <td><?= $e['student'] ?></td>
                                        <td><?= $e['course'] ?></td>
                                        <td><?= $e['status'] ?></td>
                                        <!-- ACTIONS -->
                                        <td class="py-3 text-right">
                                            <div class="flex justify-end gap-3">

                                                <!-- EDIT -->
                                                <a href="./form_Edit_User.php?id=<?= $e['id'] ?>">
                                                    <button
                                                        class="text-blue-400 hover:text-blue-300">
                                                        Edit
                                                    </button>
                                                </a>

                                                <!-- DELETE -->
                                                <a href="delet_enr.php?id=<?= $e['id'] ?>"
                                                    onclick="return confirm('Delete this user?')"
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