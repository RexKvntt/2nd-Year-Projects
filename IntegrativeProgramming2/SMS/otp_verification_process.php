<?php
session_start();

require 'cryptograph_process.php';
require 'twilio_verify.php';

$jsonData = file_get_contents("users.json");
$data = json_decode($jsonData, true);

$inputOTP = $_POST['otp'];
$username = $_SESSION['temp_user'] ?? '';
$role = $_SESSION['temp_role'] ?? '';
$otpMethod = $_SESSION['temp_otp_method'] ?? '';

if ($username === '') {
    die("Session expired. Please log in again.");
}

//verification process

foreach ($data['users'] as $user){
    if($user['username'] === $username) {
        if ($otpMethod === 'sms') {
            $phoneNumber = decryptData($user['phonenumber']);

            if (!preg_match('/^\+63\d{10}$/', $phoneNumber)) {
                die("Invalid Philippine phone number. Please use the +63 format.");
            }
            
            if (!verifyOtpViaTwilio($phoneNumber, $inputOTP)) {
                die("OTP is invalid");
            }
        } else {
            if (!isset($user['otp_expiry']) || time() > $user['otp_expiry']) {
                die("OTP already expired!");
            }

            if($user['otp'] != $inputOTP){
                die("OTP is invalid");
            }
        }

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        unset($_SESSION['temp_user']);
        unset($_SESSION['temp_role']);
        unset($_SESSION['temp_otp_method']);

        header("Location: dashboard.php");
        exit();
    }
}

die("User not found.");
?>
