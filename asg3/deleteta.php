<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: deleteta.php
     Description: This file is used to delete a teaching assistant's record from the database.
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delete TA</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'dbconnect.php'; ?>
    <div class="back-to-menu">
       <a href="mainmenu.php" class="button">Menu</a>
    </div>
    <!-- Container for the TA deletion form -->
    <div class="container">
    <h1>Delete TA</h1> 
    <!-- Form for selecting a TA to delete -->
    <form action="deleteta.php" method="post">
        Select TA to delete: 
        <select name="userid">
            <option value="">Select a TA</option>
            <?php
            // Query to fetch all TA details
            $query = "SELECT tauserid, firstname, lastname FROM ta ORDER BY lastname, firstname";
            $result = mysqli_query($connection, $query);
            if ($result) {
                // Iterate through each TA and add them to the dropdown
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['tauserid'] . "'>" . $row['firstname'] . " " . $row['lastname'] . " (" . $row['tauserid'] . ")</option>";
                }
            }
            ?>
        </select>
        <br>
	<br>
        <input type="submit" value="Delete TA">
    </form>

    <?php
    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["userid"])) {
        $tauserid = mysqli_real_escape_string($connection, $_POST["userid"]);

        // Check if TA is assigned to a course offering
        $checkAssignment = "SELECT * FROM hasworkedon WHERE tauserid = '$tauserid'";
        $assignmentResult = mysqli_query($connection, $checkAssignment);

        if ($assignmentResult && mysqli_num_rows($assignmentResult) > 0) {
            // Prevent deletion if the TA is assigned to a course
            echo "Cannot delete TA as they are assigned to a course.";
        } else {
            $deleteQuery = "DELETE FROM ta WHERE tauserid = '$tauserid'";
            if (mysqli_query($connection, $deleteQuery) && mysqli_affected_rows($connection) > 0) {
                echo "TA deleted successfully.";
            } else {
                // Handle errors or non-existent TAs
                echo "Error deleting TA or TA not found: " . mysqli_error($connection);
            }
        }
    }
    mysqli_close($connection);
    ?>
</div>
</body>
</html>
