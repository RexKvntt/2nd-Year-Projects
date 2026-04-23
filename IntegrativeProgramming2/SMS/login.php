<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Login</h2><br>

    <form action="process_login.php" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Receive OTP via:</label><br>
        <input type="radio" name="otp_method" value="email" required>
        <label for="otp_method_email">Email</label><br>
        <input type="radio" name="otp_method" value="sms" required>
        <label for="otp_method_sms">SMS</label><br><br>

        <button type="submit">Login</button>

    </form>
</body>
</html>
