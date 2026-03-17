<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Classmate</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Classmate</h2>
        <div class="actions">
            <a href="read.php">Back to List</a>
        </div>

        <?php
        $row = null;
        if (isset($_GET['stud_no'])) {
            $stud_no_get = $_GET['stud_no'];
            $result = $conn->query("SELECT * FROM classmates_details WHERE stud_no = '$stud_no_get'");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stud_no'])) {
            $stud_no = $_POST['stud_no'];
            $lastname = $_POST['lastname'];
            $firstname = $_POST['firstname'];
            $sex = $_POST['sex'];
            $bdate = $_POST['bdate'];
            $age = empty($_POST['age']) ? "NULL" : $_POST['age'];
            $religion = $_POST['religion'];
            $talent = $_POST['talent'];

            $sql = "UPDATE classmates_details SET 
                    lastname='$lastname', 
                    firstname='$firstname',
                    sex='$sex',
                    bdate='$bdate',
                    age=$age,
                    religion='$religion',
                    talent='$talent'
                    WHERE stud_no = '$stud_no'";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>Record updated successfully.</p>";
                $result = $conn->query("SELECT * FROM classmates_details WHERE stud_no = '$stud_no'");
                $row = $result->fetch_assoc();
            } else {
                echo "<p class='error'>Error updating record: " . $conn->error . "</p>";
            }
        }
        ?>

        <?php if ($row): ?>
        <form method="POST" action="">
            <input type="hidden" name="stud_no" value="<?php echo htmlspecialchars($row['stud_no']); ?>">
            <label>Student No:</label> <input type="text" value="<?php echo htmlspecialchars($row['stud_no']); ?>" disabled style="background-color: #eee;">
            <label>Last Name:</label> <input type="text" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
            <label>First Name:</label> <input type="text" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
            <label>Sex:</label>
            <select name="sex" required>
                <option value="Male" <?php echo $row['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $row['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
            <label>Birth Date:</label> <input type="date" name="bdate" value="<?php echo htmlspecialchars($row['bdate']); ?>" required>
            <label>Age:</label> <input type="number" name="age" value="<?php echo htmlspecialchars($row['age']); ?>">
            <label>Religion:</label> <input type="text" name="religion" value="<?php echo htmlspecialchars($row['religion']); ?>" required>
            <label>Talent:</label> <input type="text" name="talent" value="<?php echo htmlspecialchars($row['talent']); ?>" required>
            <button type="submit">Update</button>
        </form>
        <?php else: ?>
            <p class="error">Record not found. Please select a record from the <a href="read.php">list</a></p>
        <?php endif; ?>
    </div>
</body>
</html>