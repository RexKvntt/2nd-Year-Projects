<?php
include 'db.php';

if (isset($_GET['stud_no'])) {
    $stud_no = $_GET['stud_no'];
    $sql = "DELETE FROM classmates_details WHERE stud_no = '$stud_no'";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the read page
        header("Location: read.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->$error;
    }
} else {
    echo "No student specified. <a href='read.php'>Back to List</a>";
}
?>