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

  <div class="container">
    <h1>Course Offerings</h1>
       <form action="courses.php" method="post" class="course-form">
          <label for="selectedCourse">Select a course:</label>
          <select id="selectedCourse" name="selectedCourse">
	    <?php
            $query = "SELECT DISTINCT coursenum, coursename FROM course ORDER BY coursenum";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Database query failed.");
            }
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
        $endYear = 2023;       // Data is only up to 2023

	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectedCourse"])) {
        $selectedCourse = mysqli_real_escape_string($connection, $_POST["selectedCourse"]);
        $courseQuery = "SELECT * FROM courseoffer WHERE whichcourse='{$selectedCourse}'";

	if (!empty($_POST["startYear"]) && !empty($_POST["endYear"])) {
       	  $startYear = intval($_POST["startYear"]);
          $endYear = intval($_POST["endYear"]);
        // Append the date filtering to the query
          $courseQuery .= " AND year BETWEEN '{$startYear}' AND '{$endYear}'";
    }

	 $courseQuery .= " ORDER BY year, term";
        $courseResult = mysqli_query($connection, $courseQuery);
        if (!$courseResult) {
            die("Database query failed.");
        }

        echo "<h2>Offerings for Course: " . htmlspecialchars($selectedCourse) . "</h2>";

        if (mysqli_num_rows($courseResult) > 0) {
            echo "<table>";
            echo "<tr><th>Offering ID</th><th>Student Count</th><th>Term</th><th>Year</th></tr>";
            while ($courseRow = mysqli_fetch_assoc($courseResult)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($courseRow['coid']) . "</td>";
                echo "<td>" . htmlspecialchars($courseRow['numstudent']) . "</td>";
                echo "<td>" . htmlspecialchars($courseRow['term']) . "</td>";
                echo "<td>" . htmlspecialchars($courseRow['year']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No offerings found for this course or within the specified years.";
        }
        mysqli_free_result($courseResult);
    }

    mysqli_close($connection);
    ?>
</body>
</html>
