<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli('localhost', 'root', '', 'student_management_db');
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    die('Connection failed: ' . $exception->getMessage());
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function calculate_age(string $birthdate): int
{
    return (int) date_diff(date_create($birthdate), date_create('today'))->y;
}

function selected($value, $currentValue): string
{
    return (string) $value === (string) $currentValue ? 'selected' : '';
}

function fetch_options(mysqli $conn, string $table, string $idColumn, string $labelColumn): array
{
    $sql = sprintf(
        'SELECT %s, %s FROM %s ORDER BY %s ASC',
        $idColumn,
        $labelColumn,
        $table,
        $labelColumn
    );

    $result = $conn->query($sql);
    $options = [];

    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }

    return $options;
}

function get_status_message(string $status): string
{
    $messages = [
        'student_added' => 'Student record added successfully.',
        'student_updated' => 'Student record updated successfully.',
        'student_deleted' => 'Student record deleted successfully.',
        'grade_added' => 'Grade added successfully.',
        'invalid_student' => 'The selected student record could not be found.',
        'invalid_request' => 'The request could not be processed.',
        'delete_failed' => 'Unable to delete the student record.',
    ];

    return $messages[$status] ?? '';
}
?>
