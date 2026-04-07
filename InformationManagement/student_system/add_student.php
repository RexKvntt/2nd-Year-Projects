<?php
require_once 'config.php';

$courses = fetch_options($conn, 'course', 'course_id', 'course_name');
$sections = fetch_options($conn, 'section', 'section_id', 'section_name');
$genders = fetch_options($conn, 'gender', 'gender_id', 'gender_name');

$formData = [
    'first_name' => '',
    'last_name' => '',
    'birthdate' => '',
    'course_id' => '',
    'section_id' => '',
    'gender_id' => '',
];

$errors = [];
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    $formData['first_name'] = trim($_POST['first_name'] ?? '');
    $formData['last_name'] = trim($_POST['last_name'] ?? '');
    $formData['birthdate'] = $_POST['birthdate'] ?? '';
    $formData['course_id'] = $_POST['course_id'] ?? '';
    $formData['section_id'] = $_POST['section_id'] ?? '';
    $formData['gender_id'] = $_POST['gender_id'] ?? '';

    if ($formData['first_name'] === '') {
        $errors[] = 'First name is required.';
    }

    if ($formData['last_name'] === '') {
        $errors[] = 'Last name is required.';
    }

    if ($formData['birthdate'] === '') {
        $errors[] = 'Birthdate is required.';
    } elseif ($formData['birthdate'] > date('Y-m-d')) {
        $errors[] = 'Birthdate cannot be in the future.';
    }

    if (!filter_var($formData['course_id'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Please select a course.';
    }

    if (!filter_var($formData['section_id'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Please select a section.';
    }

    if (!filter_var($formData['gender_id'], FILTER_VALIDATE_INT)) {
        $errors[] = 'Please select a gender.';
    }

    if (!$errors) {
        $statement = $conn->prepare(
            'INSERT INTO students (first_name, last_name, birthdate, course_id, section_id, gender_id)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'sssiii',
            $formData['first_name'],
            $formData['last_name'],
            $formData['birthdate'],
            $formData['course_id'],
            $formData['section_id'],
            $formData['gender_id']
        );
        $statement->execute();

        redirect('index.php?status=student_added');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <div class="page-header">
            <div>
                <h1>Add Student</h1>
                <p>Create a new student record in the system.</p>
            </div>
            <div class="page-actions">
                <a class="button secondary" href="index.php">Back to Records</a>
                <a class="button secondary" href="add_grade.php">Manage Grades</a>
            </div>
        </div>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo h($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form method="post" class="form-grid">
                <div>
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo h($formData['first_name']); ?>" required>
                </div>

                <div>
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo h($formData['last_name']); ?>" required>
                </div>

                <div>
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo h($formData['birthdate']); ?>" required>
                </div>

                <div>
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id" required>
                        <option value="">Select course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo h($course['course_id']); ?>" <?php echo selected($course['course_id'], $formData['course_id']); ?>>
                                <?php echo h($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="section_id">Section</label>
                    <select id="section_id" name="section_id" required>
                        <option value="">Select section</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo h($section['section_id']); ?>" <?php echo selected($section['section_id'], $formData['section_id']); ?>>
                                <?php echo h($section['section_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="gender_id">Gender</label>
                    <select id="gender_id" name="gender_id" required>
                        <option value="">Select gender</option>
                        <?php foreach ($genders as $gender): ?>
                            <option value="<?php echo h($gender['gender_id']); ?>" <?php echo selected($gender['gender_id'], $formData['gender_id']); ?>>
                                <?php echo h($gender['gender_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
