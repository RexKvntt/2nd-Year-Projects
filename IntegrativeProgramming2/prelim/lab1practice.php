<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="post">
    <label>Name:</label>
    <input type="text" name="name" required><br>

    <label>Phone number:</label>
    <input type="text" name="number" required><br>

    <label>Address:</label>
    <input type="text" name="address" required><br>

    <label>Email:</label>
    <input type="text" name="email" required><br><br>

    <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $phoneNumber = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    echo "Hello " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "! Your phone number is " . htmlspecialchars($phoneNumber, ENT_QUOTES, 'UTF-8') . " and you live in " . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . " and your email is $email.";
}
?>