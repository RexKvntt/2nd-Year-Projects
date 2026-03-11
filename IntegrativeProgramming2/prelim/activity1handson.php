<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Student Event Registration System</h2>
    
    <form method="post" action="">
    <label>Full name:</label>
    <input type="text" name="name" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Message:</label>
    <input type="text" name="message" required><br>

    <input type="submit" name="submit" value="Submit"><br><br>

</body>
</html>

<?php

if (isset($_POST['submit'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    echo "Thank you, <b>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</b>. You have been successfully registered in this event!<br>Please wait for further updates to be sent to your email at <b>" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</b>.<br><br>Your message:<br><b>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</b><br>Your message has been received by our organizers and will be accounted for, thank you!";
}

?>