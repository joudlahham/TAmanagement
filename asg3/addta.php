<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add New TA</title>
</head>
<body>
    <?php include 'connectdb.php'; ?>

    <h1>Add New TA:</h1>
    <form action="addta.php" method="post">
        TA User ID: <input type="text" name="userid" required><br>
        First Name: <input type="text" name="firstname" required><br>
        Last Name: <input type="text" name="lastname" required><br>
        Student Number: <input type="text" name="studentnumber" required><br>
        Degree Type: <input type="text" name="degreetype" required><br>
        <input type="submit" value="Add TA">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tauserid = mysqli_real_escape_string($connection, $_POST["userid"]);
        $firstname = mysqli_real_escape_string($connection, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($connection, $_POST["lastname"]);
        $studentnumber = mysqli_real_escape_string($connection, $_POST["studentnumber"]);
        $degreetype = mysqli_real_escape_string($connection, $_POST["degreetype"]);

        // Check if TA with same user ID or student number already exists
        $checkQuery = "SELECT * FROM ta WHERE tauserid = '$tauserid' OR studentnum = '$studentnumber'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            echo "A TA with the same User ID or Student Number already exists.";
        } else {
            $addQuery = "INSERT INTO ta (tauserid, firstname, lastname, studentnum, degreetype) VALUES ('$tauserid', '$firstname', '$lastname', '$studentnumber', '$degreetype')";
            
            if (mysqli_query($connection, $addQuery)) {
                echo "New TA added successfully.";
            } else {
                echo "Error adding new TA: " . mysqli_error($connection);
            }
        }
    }

    mysqli_close($connection);
    ?>
</body>
</html>
