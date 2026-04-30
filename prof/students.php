<?php
require_once "../scripts/connection.php";



$stmt = $conn->prepare("
    SELECT 
        courses.id,
        courses.title,
        courses.total_hours,
        COUNT(enrollments.student_id) AS total_students,
        GROUP_CONCAT(CONCAT(users.firstname, ' ', users.lastname) SEPARATOR ', ') AS students_names
    FROM courses
    LEFT JOIN enrollments 
        ON courses.id = enrollments.course_id
    LEFT JOIN students 
        ON students.id = enrollments.student_id
    LEFT JOIN users 
        ON users.id = students.user_id
    GROUP BY courses.id, courses.title, courses.total_hours
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

 <h2>My Courses</h2>

<table class="min-w-full bg-gray-900 text-white rounded-xl overflow-hidden">
    <thead class="bg-gray-800 text-gray-300 text-sm uppercase">
        <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Title</th>
            <th class="py-3 px-4 text-left">Hours</th>
            <th class="py-3 px-4 text-left">Students</th>
             <th class="py-3 px-4 text-left">name</th>
        </tr>
    </thead>

            <tbody class="divide-y divide-gray-700">
            <?php foreach($students as $student){ ?>
                <tr class="hover:bg-gray-800 transition">
                    <td class="py-3 px-4"><?= $student['id'] ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($student['title']) ?></td>
                    <td class="py-3 px-4"><?= $student['total_hours'] ?></td>
                    <td class="py-3 px-4"><?= $student['total_students'] ?></td>
                    <td class="py-3 px-4">
    <?= $student['students_names'] ?? 'No students' ?>
</td>
                </tr>
            <?php } ?>
            </tbody>
        </tr>
        <?php ?>
    </tbody>
</table>