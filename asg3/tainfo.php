// Programmer Name: Joud Al-lahham
// Student Number: 82
//

<?php require_once('dbconnect.php'); // Include the database connection ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Information</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <a href="mainmenu.php">Back to menu</a>
    <h2>TA Info</h2>
    <form action="tainfo.php" method="post">
        <div>
            <input type="radio" id="lastname" name="sortby" value="lastname" checked>
            <label for="lastname">Sort by Last Name</label>
        </div>
        <div>
            <input type="radio" id="degreetype" name="sortby" value="degreetype">
            <label for="degreetype">Sort by Degree Type</label>
        </div>
        <div>
            <input type="radio" id="asc" name="order" value="ASC" checked>
            <label for="asc">Ascending</label>
            <input type="radio" id="desc" name="order" value="DESC">
            <label for="desc">Descending</label>
        </div>
        <input type="submit" value="Sort">
    </form>
    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Student Number</th>
            <th>Degree Type</th>
        </tr>
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
   	$sortby = $_POST['sortby'];
    	$order = $_POST['order'];

   	// Validate the sortby and order inputs before using them in the query
    	$query = "SELECT * FROM ta ORDER BY " . $sortby . " " . $order;
	} else {
    	$query = "SELECT * FROM ta";
	}
	$result = mysqli_query($connection, $query);

        // Check for errors in the query
        if (!$result) {
            die("Database query failed.");
        }

        // Use returned data (if any)
        while($ta = mysqli_fetch_assoc($result)) {
            // Output data from each row
            echo "<tr>";
            echo "<td>" . $ta['tauserid'] . "</td>";
            echo "<td>" . $ta['firstname'] . "</td>";
            echo "<td>" . $ta['lastname'] . "</td>";
            echo "<td>" . $ta['studentnum'] . "</td>";
            echo "<td>" . $ta['degreetype'] . "</td>";
            echo "</tr>";
        }
        // Free the results from memory
        mysqli_free_result($result);
        ?>
    </table>
</body>
</html>
<?php
// Close database connection
mysqli_close($connection);
?>

