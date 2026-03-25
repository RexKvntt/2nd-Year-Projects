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
        <label for="fullname">Full Name: </label><br>
        <input type="text" name="fullname" required placeholder="Firstname, MI, Surname"><br><br>

        <label for="phonenumber">Phone Number: </label><br>
        <input type="text" name="phonenumber" required placeholder="(+63) 9xx xxx xxxx"><br><br>
        
        <label for="civilstatus">Civil Status: </label><br>
        <input type="text" name="civilstatus" required><br><br>

        <label for="username">Username: </label><br>
        <input type="text" name="username" required><br><br>

        <label for="email">Email: </label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Password: </label><br>
        <input type="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password: </label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>

    </form>

    <h5>Already have an account? <a href="login.php">Login</a></h5>

</body>
</html>
