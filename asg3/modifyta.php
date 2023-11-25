<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: modifyta.php
     Description:This page allows users to modify information for an existing Teaching Assistant (TA) in the database. 
     The user can update the TA's name, degree type, and courses loved or hated.
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modify TA Information</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'dbconnect.php'; ?>
<div class="container">
    <h1>Modify TA Information:</h1>
    <div class="back-to-menu">
       <a href="mainmenu.php" class="button">Menu</a>
    </div>

    <!-- Form to collect new TA information from the user -->
    <form action="modifyta.php" method="post">
        TA User ID (required): <input type="text" name="userid"><br>
        New First Name: <input type="text" name="newfirstname"><br>
        New Last Name: <input type="text" name="newlastname"><br>
        New Degree Type: <input type="text" name="newdegreetype"><br>
 
       <!-- Dropdown for selecting courses the TA loves -->        
	<h2>Courses Loved:</h2>
	<?php
	$coursesQuery = "SELECT coursenum, coursename FROM course ORDER BY coursenum";
	$coursesResult = mysqli_query($connection, $coursesQuery);
	if ($coursesResult) {
	    echo '<select name="lovedCourses[]" multiple size="5">';
	    while ($course = mysqli_fetch_assoc($coursesResult)) {
	        echo '<option value="' . $course['coursenum'] . '">' . $course['coursenum'] . ' - ' . $course['coursename'] . '</option>';
	    }
	    echo '</select>';
	}
	?>

        <!-- Dropdown for selecting courses the TA hates -->
	<h2>Courses Hated:</h2>
	<?php
	mysqli_data_seek($coursesResult, 0);
	if ($coursesResult) {
	    echo '<select name="hatedCourses[]" multiple size="5">';
	    while ($course = mysqli_fetch_assoc($coursesResult)) {
	        echo '<option value="' . $course['coursenum'] . '">' . $course['coursenum'] . ' - ' . $course['coursename'] . '</option>';
	    }
	    echo '</select>';
	}
	?>
       
	 <!-- Section to assign courses and hours worked -->   
	<div class="assign-courses">
	<label for="assignedCourse">Select Course:</label>
	<select name="assignedCourse" id="assignedCourse">
        <option value="">No Course Selected</option>
    	<?php
        // Query to get course offers for dropdown, ordered by year and course number
	$coursesQuery = "SELECT courseoffer.coid, courseoffer.year, course.coursenum, course.coursename FROM courseoffer INNER JOIN course ON courseoffer.whichcourse = course.coursenum ORDER BY courseoffer.year DESC, course.coursenum";
    	$coursesResult = mysqli_query($connection, $coursesQuery);    
	    if (!$coursesResult) {
	        echo '<option value="">Error: ' . mysqli_error($connection) . '</option>';
	    } else if (mysqli_num_rows($coursesResult) > 0) {
	        // Iterate over each course and create an option in the dropdown.
	        while ($course = mysqli_fetch_assoc($coursesResult)) {
	            echo '<option value="' . $course['coid'] . '">' . $course['year'] . ' - ' . $course['coursenum'] . ' ' . $course['coursename'] . '</option>';
	        }
	    } else {
	        echo '<option value="">No courses available</option>';
	    }
	    ?>
	</select>

        <label for="hoursWorked">Hours Worked:</label>
        <input type="number" name="hoursWorked" id="hoursWorked" min="0">
	</div>
        <input type="submit" value="Modify TA"> 
   </form>

// Processes form submission
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tauserid = mysqli_real_escape_string($connection, $_POST["userid"]);
    $newfirstname = mysqli_real_escape_string($connection, $_POST["newfirstname"]);
    $newlastname = mysqli_real_escape_string($connection, $_POST["newlastname"]);
    $newdegreetype = mysqli_real_escape_string($connection, $_POST["newdegreetype"]);
    
    // Get the assigned course ID and hours worked if provided
    $assignedCoid = isset($_POST["assignedCourse"]) ? mysqli_real_escape_string($connection, $_POST["assignedCourse"]) : null;
    $hoursWorked = isset($_POST["hoursWorked"]) ? mysqli_real_escape_string($connection, $_POST["hoursWorked"]) : null;

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
    if (!empty($updates)) {
        $query = "UPDATE ta SET " . join(", ", $updates) . " WHERE tauserid = '$tauserid'";
        if (mysqli_query($connection, $query)) {
            echo "TA updated successfully.<br>";
        } else {
            echo "Error updating TA: " . mysqli_error($connection);
        }
    }    
    // Only assign a course if both the course ID and hours worked are provided
    if (!empty($assignedCoid) && !empty($hoursWorked)) {
        $assignQuery = "INSERT INTO hasworkedon (tauserid, coid, hours) VALUES ('$tauserid', '$assignedCoid', '$hoursWorked')";
        if (mysqli_query($connection, $assignQuery)) {
            echo "Course assigned successfully.<br>";
        } else {
            echo "Error assigning course: " . mysqli_error($connection);
        }
    }
}
    mysqli_close($connection);
    ?>
</div>
</body>
</html>
