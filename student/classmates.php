<?php
session_start();
require '../scripts/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer la classe de l'étudiant
$sql = "SELECT s.class_id, c.name AS class_name, c.classroom_number
        FROM students s
        JOIN classes c ON s.class_id = c.id
        WHERE s.user_id = $user_id";
$resultat = $conn->query($sql);
$my_class = $resultat->fetch(PDO::FETCH_OBJ);

// Récupérer les camarades
$classmates = [];
if($my_class) {
    $class_id = $my_class->class_id;
    $sql2 = "SELECT u.firstname, u.lastname, u.email, s.student_number, s.dateofbirth
            FROM students s
            JOIN users u ON s.user_id = u.id
            WHERE s.class_id = $class_id AND s.user_id != $user_id
            ORDER BY u.lastname ASC";
    $resultat2 = $conn->query($sql2);
    $classmates = $resultat2->fetchAll(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classmates - EduSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #080B14; color: #e8eaf0; }
        .card { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .sidebar { background: rgba(255,255,255,0.03); width: 250px; padding: 20px; }
        .classmate-card { border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; text-align: center; }
        .classmate-card:hover { background: rgba(255,255,255,0.08); transform: translateY(-3px); }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    </style>
</head>
<body>

<div style="display: flex; min-height: 100vh;">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="color: #6366f1; margin-bottom: 30px;">EduSync</h2>
        <a href="dashboard.php" style="color: gray; display: block; margin-bottom: 15px;">🏠 Dashboard</a>
        <a href="profile.php" style="color: gray; display: block; margin-bottom: 15px;">👤 Profile</a>
        <a href="courses.php" style="color: gray; display: block; margin-bottom: 15px;">📚 Courses</a>
        <a href="classmates.php" style="color: #818cf8; display: block; margin-bottom: 30px;">👥 Classmates</a>
        
        <form action="../scripts/logout.php" method="POST">
            <button type="submit" style="color: #ef4444;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 30px;">
        <h1 style="font-size: 32px; margin-bottom: 20px;">My Classmates</h1>
        
        <?php if($my_class): ?>
            <!-- Class Info -->
            <div class="card">
                <h2><?php echo htmlspecialchars($my_class->class_name); ?></h2>
                <p>Room <?php echo htmlspecialchars($my_class->classroom_number); ?></p>
                <p><?php echo count($classmates); ?> classmate<?php echo count($classmates) != 1 ? 's' : ''; ?></p>
            </div>

            <!-- Search Box -->
            <input type="text" id="searchInput" placeholder="Search classmates..." 
                   style="width: 100%; padding: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; margin-bottom: 20px;"
                   onkeyup="searchClassmates()">

            <!-- Classmates Grid -->
            <?php if(count($classmates) > 0): ?>
                <div class="grid" id="classmatesGrid">
                    <?php foreach($classmates as $cm): 
                        $age = date_diff(date_create($cm->dateofbirth), date_create('today'))->y;
                        $colors = ['#6366f1', '#a855f7', '#14b8a6', '#f59e0b', '#10b981', '#f43f5e'];
                        $color = $colors[array_rand($colors)];
                    ?>
                    <div class="classmate-card" data-name="<?php echo strtolower($cm->firstname . ' ' . $cm->lastname); ?>">
                        <div style="width: 60px; height: 60px; background: <?php echo $color; ?>; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                            <?php echo strtoupper(substr($cm->firstname, 0, 1) . substr($cm->lastname, 0, 1)); ?>
                        </div>
                        <h3 style="margin-bottom: 5px;"><?php echo htmlspecialchars($cm->firstname . ' ' . $cm->lastname); ?></h3>
                        <p style="color: #818cf8; font-size: 12px; margin-bottom: 10px;"><?php echo htmlspecialchars($cm->student_number); ?></p>
                        <div style="font-size: 12px; color: gray;">
                            <div>📧 <?php echo htmlspecialchars($cm->email); ?></div>
                            <div>🎂 <?php echo $age; ?> years old</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="noResults" style="display: none; text-align: center; padding: 40px;">
                    <p>No classmates found.</p>
                </div>
            <?php else: ?>
                <div class="card" style="text-align: center;">
                    <p>No classmates yet. You're the only one in your class!</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="card" style="text-align: center;">
                <p>You are not assigned to any class yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function searchClassmates() {
    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let grid = document.getElementById('classmatesGrid');
    if(!grid) return;
    
    let cards = grid.getElementsByClassName('classmate-card');
    let visible = 0;
    
    for(let i = 0; i < cards.length; i++) {
        let name = cards[i].getAttribute('data-name');
        if(name.includes(filter)) {
            cards[i].style.display = '';
            visible++;
        } else {
            cards[i].style.display = 'none';
        }
    }
    
    let noResults = document.getElementById('noResults');
    if(noResults) {
        noResults.style.display = visible === 0 ? 'block' : 'none';
    }
}
</script>

</body>
</html>