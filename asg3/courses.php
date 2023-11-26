<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: courses.php
     Description: This file serves as a portal to view the course offerings from a database.
     It includes a form that allows users to select a specific course and specify a year range to filter the offerings. 
     Upon form submission, it queries the database for relevant course offerings and presents the data in a table format. 
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Offerings</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'dbconnect.php'; ?>
<div class="header">
    <div class="back-to-menu">
    <a href="mainmenu.php" class="button">Menu</a>
    </div>
</div>
  <!-- Container for course offerings form -->
  <div class="container">
    <h1>Course Offerings</h1>
       <form action="courses.php" method="post" class="course-form">
          <label for="selectedCourse">Select a course:</label>
          <select id="selectedCourse" name="selectedCourse">
	    <?php
            // Query to retrieve all course numbers and names
            $query = "SELECT DISTINCT coursenum, coursename FROM course ORDER BY coursenum";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Database query failed.");
            }
            // Populate the dropdown with course options
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . htmlspecialchars($row['coursenum']) . "'>" . htmlspecialchars($row['coursenum']) . " - " . htmlspecialchars($row['coursename']) . "</option>";
            }
            mysqli_free_result($result);
            ?>
        </select>
        <label for="startYear">Start Year:</label>
        <input type="number" id="startYear" name="startYear" min="1900" max="2100">
        <label for="endYear">End Year:</label>
        <input type="number" id="endYear" name="endYear" min="1900" max="2100">
        <input type="submit" value="View Offerings" class="button">
    </form>
 </div>
    <?php
	// Initialize default values for $startYear and $endYear
        $startYear = 1878;     // Year Western University was founded
        $endYear = 2023;       // Current Year

        // Handle form submission
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectedCourse"])) {
        $selectedCourse = mysqli_real_escape_string($connection, $_POST["selectedCourse"]);
        // Build query to get course offerings for the selected course
        $courseQuery = "SELECT * FROM courseoffer WHERE whichcourse='{$selectedCourse}'";

        // If start and end years are provided, add them to the query for filtering
	if (!empty($_POST["startYear"]) && !empty($_POST["endYear"])) {
       	  $startYear = intval($_POST["startYear"]);
          $endYear = intval($_POST["endYear"]);
        // Append the date filtering to the query
          $courseQuery .= " AND year BETWEEN '{$startYear}' AND '{$endYear}'";
	}
        // Complete the query with ordering
	$courseQuery .= " ORDER BY year, term";
        $courseResult = mysqli_query($connection, $courseQuery);
        if (!$courseResult) {
            die("Database query failed.");
        }
	// Initialize $taDetails to an empty string
	$taDetails = '';

	// Display the course offerings in a table
	echo "<h2>Offerings for Course: " . htmlspecialchars($selectedCourse) . "</h2>";
	if (mysqli_num_rows($courseResult) > 0) {
	    echo "<table>";
	    echo "<tr><th>Offering ID</th><th>Student Count</th><th>Term</th><th>Year</th><th>TA Details</th></tr>";
	    while ($courseRow = mysqli_fetch_assoc($courseResult)) {
	        echo "<tr>";
	        echo "<td>" . htmlspecialchars($courseRow['coid']) . "</td>";
	        echo "<td>" . htmlspecialchars($courseRow['numstudent']) . "</td>";
	        echo "<td>" . htmlspecialchars($courseRow['term']) . "</td>";
	        echo "<td>" . htmlspecialchars($courseRow['year']) . "</td>";

	        // Query to get TA details for the current course offering
	        $taQuery = "SELECT ta.firstname, ta.lastname, ta.tauserid 
	                    FROM ta 
	                    JOIN hasworkedon ON ta.tauserid = hasworkedon.tauserid 
	                    WHERE hasworkedon.coid = '{$courseRow['coid']}'";
	        $taResult = mysqli_query($connection, $taQuery);

	        if ($taResult && mysqli_num_rows($taResult) > 0) {
	            // Display TA details for the offering
	            $taDetails = '';
	            while ($ta = mysqli_fetch_assoc($taResult)) {
	                $taDetails .= htmlspecialchars($ta['firstname']) . " " 
	                    . htmlspecialchars($ta['lastname']) . " (" 
	                    . htmlspecialchars($ta['tauserid']) . ")<br>";
	            }
	            echo "<td>" . $taDetails . "</td>"; // Ensure the TA details are in the same <td>
	        } else {
	            echo "<td>No TAs found for this offering</td>";
	        }
	        echo "</tr>"; // Close the table row here
	        // Free TA result set
	        mysqli_free_result($taResult);
	    }
	    echo "</table>";
	} else {
	    echo "No offerings found for this course or within the specified years.";
	}

        mysqli_free_result($courseResult); // Free the result set
    }
    mysqli_close($connection);
    ?>
</body>
</html>
