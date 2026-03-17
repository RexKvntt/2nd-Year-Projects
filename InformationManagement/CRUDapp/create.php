<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create classmate</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Add New classmate</h2>
        <div class="actions">
            <a href="index.php">Back to Home</a> | <a href="read.php">View Records</a>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // note: stud_no is removed here since it's an auto-incrementing ID in the database per your db.php update
            $lastname = $_POST['lastname'];
            $firstname = $_POST['firstname'];
            $sex = $_POST['sex'];
            $bdate = $_POST['bdate'];
            $age = empty($_POST['age']) ? "NULL" : $_POST['age'];
            $religion = $_POST['religion'];
            $talent = $_POST['talent'];

            //Insert query without stud_no
            $sql = "INSERT INTO classmates_details (lastname, firstname, sex, bdate, age, religion, talent)
            VALUES ('$lastname', '$firstname', '$sex', '$bdate', '$age', '$religion', '$talent')";

            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>New record created successfully.</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
        ?>

        <form method="POST" action="">
            <label>Last Name:</label> <input type="text" name="lastname" required>
            <label>First Name:</label> <input type="text" name="firstname" required>
            <label>Sex:</label>
            <select name="sex">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label>Birth Date:</label> <input type="date" name="bdate" required>
            <label>Age:</label> <input type="number" name="age" required>
            <label>Religion:</label> <input type="text" name="religion" required>
            <label>Talent:</label> <input type="text" name="talent" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>