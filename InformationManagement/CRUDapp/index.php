<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classmates CRUD App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Classmates CRUD App</h1>
        <p style="text-align: center;">Database Connection Status: <b style="color: #2eec71">Connected Successfully.</b></p>

        <h2 style="margin-top: 40px;">Features</h2>
        <div class="actions">
            <a href="create.php" class="btn">Create (Add new classmate)</a>
            <a href="read.php" class="btn">Read (View, Filter, and Sort Classmates)</a>
        </div>
    </div>
</body>
</html>