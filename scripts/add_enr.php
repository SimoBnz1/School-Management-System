<?php
require '../scripts/connection.php';

session_start();

// security
if (!isset($_SESSION['role']) || $_SESSION['role'] != "1") {
    header('Location: ../public/login.php');
    exit();
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* ===================== CHECK ===================== */
function checkEnr($conn, $student_id, $course_id)
{
    $sql = "SELECT * FROM enrollments 
            WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$student_id, $course_id]);

    return $stmt->rowCount() > 0;
}

/* ===================== INSERT ===================== */
function addEnr($conn, $student_id, $course_id, $status)
{
    $sql = "INSERT INTO enrollments (student_id, course_id, status)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$student_id, $course_id, $status]);
}

/* ===================== POST ===================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = $_POST['student_id'] ?? null;
    $course_id  = $_POST['course_id'] ?? null;
    $status     = $_POST['status'] ?? 'Actif';

    // validation
    if (!empty($student_id) && !empty($course_id)) {

        // check duplicate
        if (checkEnr($conn, $student_id, $course_id)) {
            header("Location: ../admin/enrollement/form_Add_enr.php?err=already");
            exit();
        }

        // insert
        addEnr($conn, $student_id, $course_id, $status);

        header("Location: ../admin/enrollement/enrollments.php?success=1");
        exit();

    } else {
        header("Location: ../admin/enrollement/form_Add_enr.php?err=empty");
        exit();
    }
}
?>