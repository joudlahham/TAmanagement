<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: tainfo.php
     Description: This script displays a sortable list of teaching assistants (TAs) from a database. 
     The user can sort the list by last name or degree type in ascending or descending order. 
-->

<?php require_once('dbconnect.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Information</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
   <div class="back-to-menu">
    <a href="mainmenu.php" class="button">Menu</a>
  </div>
  <div class="container">
    <h1>TA Information</h1>

    <!-- Form to sort TAs by last name or degree type -->
    <form action="tainfo.php" method="post">
    <label for="sortby">Sort by:</label>
    <select id="sortby" name="sortby">
        <option value="lastname">Last Name</option>
        <option value="degreetype">Degree Type</option>
    </select>

    <label for="order">Order:</label>
    <select id="order" name="order">
        <option value="ASC">Ascending</option>
        <option value="DESC">Descending</option>
    </select>

    <input type="submit" value="Sort" class="button">
    </form>

    <!-- Table to display TA information -->
    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Student Number</th>
            <th>Degree Type</th>
            <th>Details</th>
        </tr>
</div>
        <?php
        // Initialize where clause and order by default values
        $whereClause = '';
        if (isset($_POST['degreeFilter'])) {
            $degreeFilter = mysqli_real_escape_string($connection, $_POST['degreeFilter']);
            $whereClause = "WHERE degreetype = '{$degreeFilter}'";
        }

        $orderBy = 'lastname ASC'; // default order
        // Check if form was submitted to filter TAs by degree type
        if (isset($_POST['sortby']) && isset($_POST['order'])) {
            $sortby = mysqli_real_escape_string($connection, $_POST['sortby']);
            $order = mysqli_real_escape_string($connection, $_POST['order']);
            $orderBy = "{$sortby} {$order}";
        }
        // Fetch TA data with optional filtering and sorting
        $query = "SELECT * FROM ta {$whereClause} ORDER BY {$orderBy}";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Database query failed.");
        }

        while ($ta = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($ta['tauserid']) . "</td>";
            echo "<td>" . htmlspecialchars($ta['firstname']) . "</td>";
            echo "<td>" . htmlspecialchars($ta['lastname']) . "</td>";
            echo "<td>" . htmlspecialchars($ta['studentnum']) . "</td>";
            echo "<td>" . htmlspecialchars($ta['degreetype']) . "</td>";
            echo "<td><a href='tadetails.php?taid=" . urlencode($ta['tauserid']) . "'>View Details</a></td>";
            echo "</tr>";
        }

        mysqli_free_result($result);
        ?>
    </table>
</body>
</html>
<?php
mysqli_close($connection);
?>
