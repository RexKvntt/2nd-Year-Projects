<?php

session_start();

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$jsonData = file_get_contents("users.json");
$data = json_decode($jsonData, true);

$inputUser = $_POST['username'];
$inputPassword = $_POST['password'];
// $valid = false;

//check if user exists
foreach ($data['users'] as &$user){
    if($user['username'] === $inputUser){
        if(password_verify($inputPassword, $user['password'])){

            //Generate OTP
            $otp = rand(100000, 999999);
            $user['otp'] = $otp;
            $user['otp_expiry'] = time() + 300; //OTP valid for 5 minutes

            file_put_contents("users.json", json_encode($data, JSON_PRETTY_PRINT));

            $_SESSION['temp_user'] = $inputUser;

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sample@gmail.com';
            $mail->Password = 'app_password'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sample@gmail.com', 'Login Security');
            $mail->addAddress($user['email']);

            $mail->Subject = 'Your OTP Code';
            $mail->Body = "Your OTP code is: $otp. It is only valid for 5 minutes.";

            try {
                $mail->send();

                header("Location: otp_verification.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                exit();
            }

            //$valid = true;
            //$_SESSION['username'] = $inputUser;
        }else{
            echo "Invalid password.";
            exit();
        }

        break;
    }
}

    echo "Invalid username and password!";


?>
