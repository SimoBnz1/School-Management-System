<?php
session_start();
require '../scripts/connection.php'; // تأكد من المسار ديال ملف الكونيكسيون

// Middleware de sécurité : Vérifier si connecté ET si c'est un Student (Role_id = 3)
// افترضنا أنك فـ Login خبيتي l'id ديال اليوزر فـ $_SESSION['user_id']
// if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
//     header('Location: ../public/login.php');
//     exit(); 
// }

$user_id = $_SESSION['user_id'];

// 1. REQUÊTE: معلومات التلميذ والقسم ديالو
try {
    $req_info = "SELECT u.firstname, u.lastname, s.student_number, c.name AS class_name, c.classroom_number
                 FROM users u
                 JOIN students s ON u.id = s.user_id
                 JOIN classes c ON s.class_id = c.id
                 WHERE u.id = :user_id";
                 
    $stmt_info = $conn->prepare($req_info);
    $stmt_info->execute([':user_id' => $user_id]);
    $student_info = $stmt_info->fetch(PDO::FETCH_OBJ);
    
} catch (PDOException $e) {
    echo "Erreur Info : " . $e->getMessage();
}

// 2. REQUÊTE: المواد لي مسجل فيهم التلميذ + سمية الأستاذ
try {
    $req_cours = "SELECT c.title, c.description, c.total_hours, e.status, e.enrolled_at,
                         p.firstname AS prof_firstname, p.lastname AS prof_lastname
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  JOIN students s ON e.student_id = s.id
                  JOIN users p ON c.user_id = p.id  -- كنجبدو معلومات الأستاذ
                  WHERE s.user_id = :user_id";
                  
    $stmt_cours = $conn->prepare($req_cours);
    $stmt_cours->execute([':user_id' => $user_id]);
    $mes_cours = $stmt_cours->fetchAll(PDO::FETCH_OBJ);
    
} catch (PDOException $e) {
    echo "Erreur Cours : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant - EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-extrabold text-blue-600 tracking-tight">EduSync <span class="text-sm font-medium text-gray-400">| Étudiant</span></span>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-gray-600 font-medium">
                        👋 Salut, <span class="text-blue-600 font-bold"><?php echo htmlspecialchars($student_info->firstname ); ?></span>
                    </span>
                    <a href="../scripts/logout.php" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-md text-sm font-bold transition">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-2xl shadow-md p-8 mb-8 text-white flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Bienvenue sur votre espace d'apprentissage</h1>
                <p class="text-blue-100 text-lg">Retrouvez ici vos modules, vos professeurs et votre progression.</p>
            </div>
            <div class="hidden md:block text-6xl">🎓</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-fit">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Mes Informations</h3>
                <?php if($student_info): ?>
                    <ul class="space-y-4">
                        <li>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Nom complet</span>
                            <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($student_info->firstname . ' ' . $student_info->lastname); ?></span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Numéro Étudiant</span>
                            <span class="font-mono text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded"><?php echo htmlspecialchars($student_info->student_number); ?></span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Filière / Classe</span>
                            <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($student_info->class_name); ?></span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Salle Attitrée</span>
                            <span class="font-semibold text-gray-800">Salle <?php echo htmlspecialchars($student_info->classroom_number); ?></span>
                        </li>
                    </ul>
                <?php else: ?>
                    <p class="text-red-500 text-sm">Informations non trouvées. Veuillez contacter l'administration.</p>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Mes Modules</h3>
                
                <div class="space-y-4">
                    <?php if(count($mes_cours) > 0): ?>
                        <?php foreach($mes_cours as $cours): ?>
                            <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow bg-gray-50 flex flex-col sm:flex-row justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($cours->title); ?></h4>
                                    <p class="text-sm text-gray-500 italic mt-1"><?php echo htmlspecialchars($cours->description); ?></p>
                                    
                                    <div class="flex items-center gap-4 mt-3">
                                        <span class="text-xs font-semibold text-gray-600 flex items-center gap-1">
                                            ⏱️ <?php echo htmlspecialchars($cours->total_hours); ?> Heures
                                        </span>
                                        <span class="text-xs font-semibold text-indigo-600 flex items-center gap-1">
                                            👨‍🏫 Prof. <?php echo htmlspecialchars($cours->prof_lastname); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="shrink-0 flex items-center">
                                    <?php 
                                    $statusClass = (strtolower($cours->status) === 'actif') 
                                        ? 'bg-green-100 text-green-700 border-green-200' 
                                        : 'bg-gray-100 text-gray-500 border-gray-200';
                                    ?>
                                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full border <?php echo $statusClass ?>">
                                        <?php echo htmlspecialchars($cours->status); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 italic text-center p-4">Vous n'êtes inscrit à aucun module pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
