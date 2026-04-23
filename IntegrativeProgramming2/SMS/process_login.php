<?php
session_start();

require 'cryptograph_process.php';
require 'twilio_verify.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

$jsonData = file_get_contents("users.json");
$data = json_decode($jsonData, true);

$inputUser = trim($_POST['username']);
$inputPassword = $_POST['password'];
$otpMethod = $_POST['otp_method'] ?? '';

if ($otpMethod !== 'email' && $otpMethod !== 'sms') {
    die("Invalid OTP delivery method selected.");
}

//check if user exists

foreach ($data['users'] as &$user){
    if($user['username'] === $inputUser){
        //verify hashed user
    if(password_verify($inputPassword, $user['password'])){
    $_SESSION['temp_user'] = $inputUser;
    $_SESSION['temp_role'] = $user['role'] ?? '';
    $_SESSION['temp_otp_method'] = $otpMethod;

    if ($otpMethod === 'email') {
        $otp = rand(100000, 999999);
        $user['otp'] = $otp;
        $user['otp_expiry'] = time() + 300; //5minutes expiration

        file_put_contents("users.json", json_encode($data, JSON_PRETTY_PRINT));

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;
        $mail->Username = 'yourmother@gmail.com';
        $mail->Password = 'pass word hier lolz';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourmother@gmail.com', 'Login Security');
        $mail->addAddress(decryptData($user['email']));

        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your Login OTP code is: $otp";

        try {
            $mail->send();
            header("Location: otp_verification.php");
            exit();
        } catch (MailException $e) {
            die("Email could not be sent. Error: " . $mail->ErrorInfo);
        }
    } else {
        $phoneNumber = decryptData($user['phonenumber']);

        if (!preg_match('/^\+63\d{10}$/', $phoneNumber)) {
            die("Invalid Philippine phone number. Please use the +63 format.");
        }

        if (sendOtpViaTwilio($phoneNumber)) {
            header("Location: otp_verification.php");
            exit();
        }
    }

        //$valid = true;
    
        //$_SESSION['username'] = $inputUser;
    }else{
        echo "Invalid Password.";
        exit();
    }
        break;
    }
}

//login result
    echo "Invalid username and password!";
?>
