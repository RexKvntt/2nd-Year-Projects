<?php

session_start();

$role = $_SESSION['role'];

// Verify username stored on session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//role verification on session vs variable
if ($_SESSION['role'] !== $role) {
    die("Access Denied. You do not have permission to access this page.");
}

echo "<h1>Welcome, {$_SESSION['username']}</h1>";
echo "<h2>Dashboard Controls</h2>";

if ($role === "admin") {
    echo "<ul>\n<li><a href='role_admin_manageUsers.php'>Manage Users</a></li><li><a href='role_admin_systemSetting.php'>System Settings</a></li></ul>";
} elseif ($role === "faculty") {
    echo "<ul>\n<li><a href='role_faculty_uploadMaterial.php'>Upload Material</a></li><li><a href='role_faculty_manageGrades.php'>Manage Grades</a></li></ul>";
} elseif ($role === "student") {
    echo "<ul>\n<li><a href='role_student_viewMaterial.php'>View Material</a></li><li><a href='role_student_submitAssignment.php'>Submit Assignment</a></li></ul>";
} else {
    echo "No dashboard available.";
}


?>
