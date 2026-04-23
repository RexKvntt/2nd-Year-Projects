<?php
session_start();
require_once 'cryptograph_process.php';

if (isset($_SESSION['user'])) {
    $loggedInUser = $_SESSION['user'];
    $userData = file_exists('users.json') ? json_decode(file_get_contents('users.json'), true) : ['users' => []];

    // Loop through users to find the one logging out
    foreach ($userData['users'] as &$user) {
        if ($user['username'] === $loggedInUser) {
            // Update status to Inactive (Encrypted)
            $user['status'] = encryptData('Inactive');
            break;
        }
    }

    // Save the updated status back to JSON
    file_put_contents('users.json', json_encode($userData, JSON_PRETTY_PRINT), LOCK_EX);
}

// Clear all session data
$_SESSION = array();
session_destroy();

header("Location: login.php?loggedout=true");
exit();