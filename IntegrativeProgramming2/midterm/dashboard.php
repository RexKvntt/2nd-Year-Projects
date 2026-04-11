<?php
session_start();

$role = $_SESSION['role'] ?? null;
$username = $_SESSION['username'] ?? null;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//role verification on session vs variable
if ($_SESSION['role'] !== $role) {
    die("Access Denied. You do not have permission to access this page.");
}

$dashboardLinks = [];

if ($role === "admin") {
    $dashboardLinks = [
        "Manage Users" => "role_admin_manageUsers.php",
        "System Settings" => "role_admin_systemSetting.php",
    ];
} elseif ($role === "faculty") {
    $dashboardLinks = [
        "Upload Material" => "role_faculty_uploadMaterial.php",
        "Manage Grades" => "role_faculty_manageGrades.php",
    ];
} elseif ($role === "student") {
    $dashboardLinks = [
        "View Material" => "role_student_viewMaterial.php",
        "Submit Assignment" => "role_student_submitAssignment.php",
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OreXis - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <main class="app-shell dashboard-shell">
        <section class="visual-panel">
            <div class="visual-copy">
                <p class="eyebrow"><?php echo htmlspecialchars(strtoupper($role)); ?> Workspace</p>
                <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
                <p class="lead">Your dashboard keeps the same visual DNA as the authentication screens while staying clear and task-focused.</p>
            </div>
        </section>

        <section class="content-panel">
            <div class="content-card">
                <p class="brand">Dashboard Controls</p>
                <h2>Available modules</h2>
                <p class="lead">Choose one of the tools below to continue working inside your assigned role.</p>

                <?php if ($dashboardLinks): ?>
                    <ul class="action-list">
                        <?php foreach ($dashboardLinks as $label => $href): ?>
                            <li><a href="<?php echo htmlspecialchars($href); ?>"><?php echo htmlspecialchars($label); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="support-copy">No dashboard modules are available for this account.</p>
                <?php endif; ?>

                <p class="support-copy"><a href="login.php">Switch account</a></p>
            </div>
        </section>
    </main>
</body>
</html>
