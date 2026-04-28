<?php

require '../scripts/connection.php';


$prof_id = $_SESSION['id'] ?? null;

if (!$prof_id) {
    die("User not logged in");
}

$stmt = $conn->prepare("
    SELECT *
    FROM classes
    JOIN students ON students.class_id = classes.id
    JOIN enrollments ON enrollments.student_id = students.id
    JOIN courses ON courses.id = enrollments.course_id
    WHERE courses.user_id = ?
");

$stmt->execute([$prof_id]);

$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<table class="min-w-full bg-gray-900 text-white rounded-xl overflow-hidden">
    <thead class="bg-gray-800 text-gray-300 text-sm uppercase">
        <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Class Name</th>
            <th class="py-3 px-4 text-left">Level</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-700">
        <?php foreach ($classes as $class): ?>
            <tr class="hover:bg-gray-800 transition">
                <td class="py-3 px-4"><?php echo $class['id']; ?></td>
                <td class="py-3 px-4"><?php echo $class['name']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>