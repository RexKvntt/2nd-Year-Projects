<?php
require_once 'config.php';

$courses = fetch_options($conn, 'course', 'course_id', 'course_name');
$genders = fetch_options($conn, 'gender', 'gender_id', 'gender_name');

$sort = $_GET['sort'] ?? 'name';
$direction = strtolower($_GET['direction'] ?? 'asc');
$courseFilter = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
$genderFilter = filter_input(INPUT_GET, 'gender_id', FILTER_VALIDATE_INT);

$allowedSorts = [
    'name' => 's.first_name',
    'age' => 'TIMESTAMPDIFF(YEAR, s.birthdate, CURDATE())',
    'grade' => 'COALESCE(AVG(sg.grade), 0)',
];

$allowedDirections = ['asc', 'desc'];

if (!array_key_exists($sort, $allowedSorts)) {
    $sort = 'name';
}

if (!in_array($direction, $allowedDirections, true)) {
    $direction = 'asc';
}

$whereClauses = [];
$parameterTypes = '';
$parameters = [];

if ($courseFilter) {
    $whereClauses[] = 's.course_id = ?';
    $parameterTypes .= 'i';
    $parameters[] = $courseFilter;
}

if ($genderFilter) {
    $whereClauses[] = 's.gender_id = ?';
    $parameterTypes .= 'i';
    $parameters[] = $genderFilter;
}

$whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
$orderSql = $allowedSorts[$sort] . ' ' . strtoupper($direction) . ', s.last_name ASC';

$query = "
    SELECT
        s.student_id,
        s.first_name,
        s.last_name,
        s.birthdate,
        c.course_name,
        sec.section_name,
        g.gender_name,
        COALESCE(ROUND(AVG(sg.grade), 2), 0) AS average_grade,
        GROUP_CONCAT(
            CONCAT(sub.subject_name, ' (', FORMAT(sg.grade, 2), ')')
            ORDER BY sub.subject_name ASC
            SEPARATOR ', '
        ) AS subjects_and_grades
    FROM students s
    INNER JOIN course c ON s.course_id = c.course_id
    INNER JOIN section sec ON s.section_id = sec.section_id
    INNER JOIN gender g ON s.gender_id = g.gender_id
    LEFT JOIN student_grades sg ON s.student_id = sg.student_id
    LEFT JOIN subject sub ON sg.subject_id = sub.subject_id
    $whereSql
    GROUP BY
        s.student_id,
        s.first_name,
        s.last_name,
        s.birthdate,
        c.course_name,
        sec.section_name,
        g.gender_name
    ORDER BY $orderSql
";

$statement = $conn->prepare($query);

if ($parameters) {
    $statement->bind_param($parameterTypes, ...$parameters);
}

$statement->execute();
$students = $statement->get_result();
$statusMessage = get_status_message($_GET['status'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <div class="page-header">
            <div>
                <h1>Student Information Management System</h1>
                <p>Manage student records, subjects, and grades.</p>
            </div>
            <div class="page-actions">
                <a class="button" href="add_student.php">Add Student</a>
                <a class="button secondary" href="add_grade.php">Add Grade</a>
            </div>
        </div>

        <?php if ($statusMessage !== ''): ?>
            <div class="alert success">
                <p><?php echo h($statusMessage); ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="section-heading">
                <h2>Sort and Filter</h2>
                <p>Use the controls below to sort by name, age, or grade and filter by course or gender.</p>
            </div>

            <form method="get" class="filter-grid">
                <div>
                    <label for="sort">Sort By</label>
                    <select id="sort" name="sort">
                        <option value="name" <?php echo selected('name', $sort); ?>>Name</option>
                        <option value="age" <?php echo selected('age', $sort); ?>>Age</option>
                        <option value="grade" <?php echo selected('grade', $sort); ?>>Grade</option>
                    </select>
                </div>

                <div>
                    <label for="direction">Direction</label>
                    <select id="direction" name="direction">
                        <option value="asc" <?php echo selected('asc', $direction); ?>>Ascending</option>
                        <option value="desc" <?php echo selected('desc', $direction); ?>>Descending</option>
                    </select>
                </div>

                <div>
                    <label for="course_id">Filter by Course</label>
                    <select id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo h($course['course_id']); ?>" <?php echo selected($course['course_id'], $courseFilter); ?>>
                                <?php echo h($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="gender_id">Filter by Gender</label>
                    <select id="gender_id" name="gender_id">
                        <option value="">All Genders</option>
                        <?php foreach ($genders as $gender): ?>
                            <option value="<?php echo h($gender['gender_id']); ?>" <?php echo selected($gender['gender_id'], $genderFilter); ?>>
                                <?php echo h($gender['gender_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions filter-actions">
                    <button type="submit" class="button">Apply</button>
                    <a class="button secondary" href="index.php">Reset</a>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="section-heading">
                <h2>Student Records</h2>
                <p>Age is computed from birthdate and grades are shown per student.</p>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Age</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Gender</th>
                            <th>Average Grade</th>
                            <th>Subjects and Grades</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students->num_rows === 0): ?>
                            <tr>
                                <td colspan="9" class="empty-state">No student records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo h($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo h($row['birthdate']); ?></td>
                                    <td><?php echo h((string) calculate_age($row['birthdate'])); ?></td>
                                    <td><?php echo h($row['course_name']); ?></td>
                                    <td><?php echo h($row['section_name']); ?></td>
                                    <td><?php echo h($row['gender_name']); ?></td>
                                    <td><?php echo h(number_format((float) $row['average_grade'], 2)); ?></td>
                                    <td><?php echo h($row['subjects_and_grades'] ?: 'No grades yet'); ?></td>
                                    <td>
                                        <div class="action-group">
                                            <a class="button small secondary" href="edit_student.php?id=<?php echo h($row['student_id']); ?>">Edit</a>
                                            <form method="post" action="delete.php" onsubmit="return confirm('Delete this student record?');">
                                                <input type="hidden" name="student_id" value="<?php echo h($row['student_id']); ?>">
                                                <button type="submit" class="button small danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
