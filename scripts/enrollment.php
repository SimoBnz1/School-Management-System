<?php 
function enrollStudent($conn, $status, $student_id, $course_id)
{
    // 1️⃣ check ila kayn deja
    $checkSql = "SELECT * FROM enrollments 
                 WHERE student_id = :student_id
                 AND course_id = :course_id";

    $stmt = $conn->prepare($checkSql);
    $stmt->execute([
        'student_id' => $student_id,
        'course_id' => $course_id
    ]);

    $exists = $stmt->fetch();

    if ($exists) {
        return "exists"; 
    }
$sql = "INSERT INTO enrollments(id_student_id,course_id,status)
            VALUES(:student_id, :course_id,:status)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        'status' => $status,
        'student_id' => $student_id,
        'course_id' => $course_id
    ]);

    return "success";
};