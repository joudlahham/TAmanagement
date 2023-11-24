<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modify TA Information</title>
</head>
<body>
    <?php include 'connectdb.php'; ?>

    <h1>Modifying TA Information:</h1>
    <form action="modifyta.php" method="post">
        TA User ID (required): <input type="text" name="userid"><br>
        New First Name: <input type="text" name="newfirstname"><br>
        New Last Name: <input type="text" name="newlastname"><br>
        New Degree Type: <input type="text" name="newdegreetype"><br>
        <input type="submit" value="Modify TA">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tauserid = mysqli_real_escape_string($connection, $_POST["userid"]);
        $newfirstname = mysqli_real_escape_string($connection, $_POST["newfirstname"]);
        $newlastname = mysqli_real_escape_string($connection, $_POST["newlastname"]);
        $newdegreetype = mysqli_real_escape_string($connection, $_POST["newdegreetype"]);

        $query = "UPDATE ta SET ";
        $updates = array();
        if (!empty($newfirstname)) {
            $updates[] = "firstname = '$newfirstname'";
        }
        if (!empty($newlastname)) {
            $updates[] = "lastname = '$newlastname'";
        }
        if (!empty($newdegreetype)) {
            $updates[] = "degreetype = '$newdegreetype'";
        }
        $query .= join(", ", $updates);
        $query .= " WHERE tauserid = '$tauserid'";

        if (!empty($updates) && mysqli_query($connection, $query)) {
            echo "TA updated successfully.<br>";
        } else {
            echo "Error updating TA: " . mysqli_error($connection);
        }
    }

    mysqli_close($connection);
    ?>
</body>
</html>
