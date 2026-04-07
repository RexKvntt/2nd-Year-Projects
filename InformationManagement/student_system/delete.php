<?php
require_once 'config.php';

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod !== 'POST') {
    redirect('index.php?status=invalid_request');
}

$studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

if (!$studentId) {
    redirect('index.php?status=invalid_student');
}

try {
    $conn->begin_transaction();

    $deleteGrades = $conn->prepare('DELETE FROM student_grades WHERE student_id = ?');
    $deleteGrades->bind_param('i', $studentId);
    $deleteGrades->execute();

    $deleteStudent = $conn->prepare('DELETE FROM students WHERE student_id = ?');
    $deleteStudent->bind_param('i', $studentId);
    $deleteStudent->execute();

    $conn->commit();

    if ($deleteStudent->affected_rows > 0) {
        redirect('index.php?status=student_deleted');
    }

    redirect('index.php?status=invalid_student');
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    redirect('index.php?status=delete_failed');
}
?>
