<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delete TA</title>
</head>
<body>
    <?php include 'connectdb.php'; ?>

    <h1>Deleting TA</h1>
    <form action="deleteta.php" method="post">
        TA User ID (required for deletion): <input type="text" name="userid"><br>
        <input type="submit" value="Delete TA">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tauserid = mysqli_real_escape_string($connection, $_POST["userid"]);

        // Check if TA is assigned to a course offering
        $checkAssignment = "SELECT * FROM hasworkedon WHERE tauserid = '$tauserid'";
        $assignmentResult = mysqli_query($connection, $checkAssignment);

        if ($assignmentResult && mysqli_num_rows($assignmentResult) > 0) {
            echo "Cannot delete TA as they are assigned to a course.";
        } else {
            $deleteQuery = "DELETE FROM ta WHERE tauserid = '$tauserid'";
            if (mysqli_query($connection, $deleteQuery) && mysqli_affected_rows($connection) > 0) {
                echo "TA deleted successfully.";
            } else {
                echo "Error deleting TA or TA not found: " . mysqli_error($connection);
            }
        }
    }

    mysqli_close($connection);
    ?>
</body>
</html>
