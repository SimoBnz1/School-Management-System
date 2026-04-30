<?php

require '../scripts/connection.php';

// $prof_id = $_SESSION['id'] ?? null;

// if (!$prof_id) {
//     die("User not logged in");
// }

$stmt = $conn->prepare("
    SELECT 
        classes.id,
        classes.name,
        classes.classroom_number,
        COUNT(students.id) AS total_students,
        enrollments.status
    FROM classes
    JOIN students ON students.class_id = classes.id
    JOIN enrollments ON enrollments.student_id = students.id
    JOIN courses ON courses.id = enrollments.course_id
    GROUP BY classes.id, classes.name
");

$stmt->execute();

$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table class="min-w-full bg-gray-900 text-white rounded-xl overflow-hidden">
    <thead class="bg-gray-800 text-gray-300 text-sm uppercase">
        <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Class Name</th>
            <th class="py-3 px-4 text-left">classroom_number</th>
            <th class="py-3 px-4 text-left">Students</th>
            <th class="py-3 px-4 text-left">status</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-700">
        <?php foreach($classes as $class){?>
            <tr class="hover:bg-gray-800 transition">
                <td class="py-3 px-4"><?= $class['id'] ?></td>
                <td class="py-3 px-4"><?= $class['name'] ?></td>
                <td class="py-3 px-4"><?= $class['classroom_number'] ?></td>
                <td class="py-3 px-4"><?= $class['total_students'] ?></td>
                <td class="py-3 px-4">
            <a href="#<?= $class['id'] ?>&status=<?= $class['status'] ?>" 
            class="px-3 py-1 rounded-lg <?= $class['status'] == 'Actif' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                <?= $class['status'] ?>
            </a>
        </td>
            </tr>
        <?php } ?>
    </tbody>
</table>