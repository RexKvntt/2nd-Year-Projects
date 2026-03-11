<?php

session_start();

$jsonData = file_get_contents("users.json");
$data = json_decode($jsonData, true);

$inputOTP = $_POST['otp'];
$username = $_SESSION['temp_user'];

//verification process

foreach ($data['users'] as $user){
    if($user['username'] === $username){
        if(time() > $user['otp_expiry']){
            die("OTP expired. Please log in again.");
        }

        if ($user['otp'] == $inputOTP){
            $_SESSION['username'] = $username;
            unset($_SESSION['temp_user']);

            header("Location: dashboard.php");
            exit();
        }else{
            echo "OTP is invalid.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Access Granted</h1>
    <p>You have been granted access to the dashboard. Redirecting...</p>
</body>
</html>

<script>
setTimeout(function() {
    window.location.href = "dashboard.php";
}, 5000);
</script>