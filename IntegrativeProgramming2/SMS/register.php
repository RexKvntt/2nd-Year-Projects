<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Register</h2><br>

    <form action="process_register.php" method="POST">

        <label for="fullname">Fullname:</label><br>
        <input type="text" name="fullname" placeholder="Firstname MI Surname" required><br><br>

        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="phonenumber">Phone Number:</label><br>
        <input type="text" name="phonenumber" placeholder="+639XXXXXXXXX" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>

    </form>

    <h6>Already have an account?<a href="login.php">Login</a></h6>
</body>
</html>
