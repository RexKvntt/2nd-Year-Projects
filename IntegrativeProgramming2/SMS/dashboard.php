<?php
session_start();

$role = $_SESSION['role'];

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//role verification on session vs var
if($_SESSION['role'] !== $role) {
    die("Access Denied: Unauthorized Role.");
}

echo "<h1>Welcome, {$_SESSION['username']}</h1>";
echo "<h2>Dashboard Controls</h2>";

if($role === "admin") {
    echo "<ul><li><a href='role_admin_manageUsers.php'>Manage Users</a></li><li><a href='role_admin_systemSettings.php'>System Settings</a></li></ul>";
} elseif ($role === "faculty") {
    echo "<ul><li><a href='role_faculty_manageGrades.php'>Manage Grades</a></li><li><a href='role_faculty_uploadMaterials.php'>Upload Materials</a></li></ul>";
} elseif ($role === "student") {
    echo "<ul><li><a href='role_student_submitAssignment.php'>Submit Assignment</a></li><li><a href='role_student_viewMaterial.php'>View Material</a></li></ul>";
} else {
    echo "No dashboard available.";
}
?>