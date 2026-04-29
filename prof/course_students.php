<?php
require_once "../scripts/connection.php";

// ADD COURSE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $hours = $_POST['hours'];

    $stmt = $conn->prepare("
        INSERT INTO courses (title, total_hours, users_id)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$title, $hours, 2]); // 2 = prof id test
}

// GET COURSES
$stmt = $conn->query("SELECT * FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Courses</h2>

<!-- FORM -->
<!-- TABLE -->
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Hours</th>
    </tr>

    <?php foreach($courses as $c){ ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['title']) ?></td>
        <td><?= $c['total_hours'] ?></td>
    </tr>
    <?php } ?>
</table>
