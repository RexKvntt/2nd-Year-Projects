<?php

require 'cryptograph_process.php';

$jsonData= file_get_contents("users.json");
$data = json_decode($jsonData, true);

$rawPhoneNumber = trim($_POST['phonenumber']);

if (!preg_match('/^\+63\d{10}$/', $rawPhoneNumber)) {
    die("Invalid Philippine phone number. Please use the +63 format.");
}

$fullname =  encryptData($_POST['fullname']);
$phonenumber = encryptData($rawPhoneNumber);
$newUser = trim($_POST['username']);
$newPassword = $_POST['password'];
$newEmail = encryptData($_POST ['email']);
$confirmPassword = $_POST ['confirm_password'];
foreach($data['users'] as $user){
    if($user['username'] === $newUser)
        die("Username already exists!");
}
//strong password policy
if (
    strlen($newPassword) < 8 ||
    !preg_match("/[A-Z]/", $newPassword) || //uppercase
    !preg_match("/[a-z]/", $newPassword) || //lowercase
    !preg_match("/[0-9]/", $newPassword) || //number
    !preg_match("/[\W]/", $newPassword)     //special chars
){
    die("Password must atleast 8 characters long and include uppercase, lowercase, number, and special characters");
}

if($newPassword !== $confirmPassword){
    die("Password do not match!");

}
//password hashing
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$data['users'][] =[
    'fullname' => $fullname,
    'phonenumber' => $phonenumber,
    'username' => $newUser,
    'email' => $newEmail,
    'password' => $hashedPassword
];

file_put_contents("users.json", json_encode($data, JSON_PRETTY_PRINT));

echo "Registered succesfully! <a href='login.php'>Login</a>";

?>
