<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: tadetails.php
     Description: This file serves as the detailed view for a Teaching Assistant (TA) in the database.
     It provides functionality to display a TA's personal information, the courses they love and hate, as well as the courses they have worked on.
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TA Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="back-to-menu">
    <a href="mainmenu.php" class="button">Menu</a>
    </div>

  <!-- Main container for TA details -->
  <div class="container">

    <?php
    include 'dbconnect.php';

    // Check if 'taid' GET parameter is set
    if (!isset($_GET['taid'])) {
        die("TA ID not specified.");
    }
    $taid = mysqli_real_escape_string($connection, $_GET['taid']);

    // Query for specific TA
    $query = "SELECT * FROM ta WHERE tauserid='$taid'";
    $ta_result = mysqli_query($connection, $query);
    if (!$ta_result) {
        die("database query failed.");
    }

    // Fetch the TA's data
    $ta = mysqli_fetch_assoc($ta_result);
    if (!$ta) {
        die("TA not found.");
    }
   
    $defaultImageUrl = "https://media.licdn.com/dms/image/D4D03AQFKngNCw1WSqw/profile-displayphoto-shrink_800_800/0/1669733427683?e=2147483647&v=beta&t=iaOYFmG49j2AdYyw0r0WOhWdx1vrdHLaZUbxbM3ujE0";
    $imageUrl = isset($ta['imageurl']) && !empty($ta['imageurl']) ? $ta['imageurl'] : $defaultImageUrl;

    // Display the TA's details
    echo "<h1>Details for TA: " . htmlspecialchars($ta['firstname']) . " " . htmlspecialchars($ta['lastname']) . "</h1>";
    echo "<img src='" . htmlspecialchars($imageUrl) . "' alt='TA Photo' width='100' height='100'>"; 
    echo "<p>User ID: " . htmlspecialchars($ta['tauserid']) . "</p>";
    echo "<p>First Name: " . htmlspecialchars($ta['firstname']) . "</p>";
    echo "<p>Last Name: " . htmlspecialchars($ta['lastname']) . "</p>";
    echo "<p>Student Number: " . htmlspecialchars($ta['studentnum']) . "</p>";
    echo "<p>Degree Type: " . htmlspecialchars($ta['degreetype']) . "</p>";

   // Display courses loved
   echo '<div class="details-section">';
   echo '<h2>Courses Loved</h2>';
   $loves_query = "SELECT course.coursenum, course.coursename FROM course JOIN loves ON course.coursenum = loves.lcoursenum WHERE loves.ltauserid='$taid'";
   $loves_result = mysqli_query($connection, $loves_query);
   if (!$loves_result) {
    die("Loves query failed: " . mysqli_error($connection));
   } 
   while ($course = mysqli_fetch_assoc($loves_result)) {
    echo "<p>" . htmlspecialchars($course['coursenum']) . " - " . htmlspecialchars($course['coursename']) . "</p>";
   }
   echo "</div>";

   // Display courses hated
   echo '<div class="details-section">';
   echo '<h2>Courses Hated</h2>';
   $hates_query = "SELECT course.coursenum, course.coursename FROM course JOIN hates ON course.coursenum = hates.hcoursenum WHERE hates.htauserid='$taid'";
   $hates_result = mysqli_query($connection, $hates_query);
   if (!$hates_result) {
     die("Hates query failed: " . mysqli_error($connection));
    }
   while ($course = mysqli_fetch_assoc($hates_result)) {
    echo "<p>" . htmlspecialchars($course['coursenum']) . " - " . htmlspecialchars($course['coursename']) . "</p>";
    }
    echo "</div>";

   // Display courses worked on
	echo '<h2>Courses Worked On</h2>';
	$worked_query = "SELECT course.coursenum, course.coursename, courseoffer.year, courseoffer.term,
	 hasworkedon.hours FROM hasworkedon JOIN courseoffer ON hasworkedon.coid = courseoffer.coid JOIN course ON courseoffer.whichcourse = course.coursenum WHERE hasworkedon.tauserid = '$taid'";
	$worked_result = mysqli_query($connection, $worked_query);
	if (!$worked_result) {

	    die("Worked on query failed: " . mysqli_error($connection));
	}
	$num_rows_worked = mysqli_num_rows($worked_result);
	if ($num_rows_worked > 0) {
	echo '<table>';
            echo '<tr><th>Course Code</th><th>Course Name</th><th>Course Term</th><th>Hours Worked</th></tr>';
            while ($work = mysqli_fetch_assoc($worked_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($work['coursenum']) . "</td>";
                echo "<td>" . htmlspecialchars($work['coursename']) . "</td>";
                echo "<td>" . htmlspecialchars($work['term']) . "</td>";
                echo "<td>" . htmlspecialchars($work['hours']) . "</td>";
                echo "</tr>";
            }
            echo '</table>';
} else {
    echo "<p>No courses worked on found.</p>";
}
   // Free the results from memory
   mysqli_free_result($ta_result);
   if ($loves_result) {
      mysqli_free_result($loves_result);
   }
   if ($hates_result) {
      mysqli_free_result($hates_result);
   }
   if ($worked_result) {
      mysqli_free_result($worked_result);
   }

    // Close database connection
    mysqli_close($connection);?>
</div>
</body>
</html>
