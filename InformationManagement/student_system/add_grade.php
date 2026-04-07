<?php
require_once 'config.php';

$studentsResult = $conn->query('SELECT student_id, first_name, last_name FROM students ORDER BY first_name ASC, last_name ASC');
$students = [];

while ($row = $studentsResult->fetch_assoc()) {
    $students[] = $row;
}

$subjects = fetch_options($conn, 'subject', 'subject_id', 'subject_name');

$formData = [
    'student_id' => '',
    'subject_id' => '',
    'grade' => '',
];

$errors = [];
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    $formData['student_id'] = $_POST['student_id'] ?? '';
    $formData['subject_id'] = $_POST['subject_id'] ?? '';
    $formData['grade'] = trim($_POST['grade'] ?? '');

    if (!filter_var($formData['student_id'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Please select a student.';
    }

    if (!filter_var($formData['subject_id'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Please select a subject.';
    }

    if ($formData['grade'] === '') {
        $errors[] = 'Grade is required.';
    } elseif (!is_numeric($formData['grade'])) {
        $errors[] = 'Grade must be a valid number.';
    } elseif ((float) $formData['grade'] < 0 || (float) $formData['grade'] > 100) {
        $errors[] = 'Grade must be between 0 and 100.';
    }

    if (!$errors) {
        $gradeValue = number_format((float) $formData['grade'], 2, '.', '');

        $statement = $conn->prepare(
            'INSERT INTO student_grades (student_id, subject_id, grade)
             VALUES (?, ?, ?)'
        );
        $statement->bind_param(
            'iid',
            $formData['student_id'],
            $formData['subject_id'],
            $gradeValue
        );
        $statement->execute();

        redirect('add_grade.php?status=grade_added');
    }
}

$gradeRecords = $conn->query(
    'SELECT sg.grade_id, s.first_name, s.last_name, sub.subject_name, sg.grade
     FROM student_grades sg
     INNER JOIN students s ON sg.student_id = s.student_id
     INNER JOIN subject sub ON sg.subject_id = sub.subject_id
     ORDER BY s.first_name ASC, s.last_name ASC, sub.subject_name ASC'
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects and Grades</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <div class="page-header">
            <div>
                <h1>Subjects and Grades</h1>
                <p>Assign a subject and grade to a student.</p>
            </div>
            <div class="page-actions">
                <a class="button secondary" href="index.php">Back to Records</a>
                <a class="button secondary" href="add_student.php">Add Student</a>
            </div>
        </div>

        <?php $statusMessage = get_status_message($_GET['status'] ?? ''); ?>
        <?php if ($statusMessage !== ''): ?>
            <div class="alert success">
                <p><?php echo h($statusMessage); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo h($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form method="post" class="form-grid compact-form">
                <div>
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Select student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo h($student['student_id']); ?>" <?php echo selected($student['student_id'], $formData['student_id']); ?>>
                                <?php echo h($student['first_name'] . ' ' . $student['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="subject_id">Subject</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">Select subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo h($subject['subject_id']); ?>" <?php echo selected($subject['subject_id'], $formData['subject_id']); ?>>
                                <?php echo h($subject['subject_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="grade">Grade</label>
                    <input type="number" id="grade" name="grade" min="0" max="100" step="0.01" value="<?php echo h($formData['grade']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button">Save Grade</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="section-heading">
                <h2>Grade Records</h2>
                <p>All subjects and grades entered so far.</p>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($gradeRow = $gradeRecords->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo h($gradeRow['first_name'] . ' ' . $gradeRow['last_name']); ?></td>
                                <td><?php echo h($gradeRow['subject_name']); ?></td>
                                <td><?php echo h(number_format((float) $gradeRow['grade'], 2)); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
