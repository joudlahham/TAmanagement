<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add New TA</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <?php include 'dbconnect.php'; ?>
    <div class="back-to-menu">
        <a href="mainmenu.php" class="button">Menu</a>
    </div>
        <h1>Add New TA:</h1>
        <form action="addta.php" method="post">
            <p> TA User ID: <input type="text" name="userid" required></p>
            <p> First Name: <input type="text" name="firstname" required></p>
            <p> Last Name: <input type="text" name="lastname" required></p>
            <p> Student Number: <input type="text" name="studentnumber" required></p>
            <p> Degree Type: <input type="text" name="degreetype" required></p>
            
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
        }?>
	
	<h2>Courses Hated:</h2>
	<?php
	mysqli_data_seek($coursesResult, 0); // Go to the beginning of the set
        if ($coursesResult) {
            echo '<select name="hatedCourses[]" multiple size="5">';
            while ($course = mysqli_fetch_assoc($coursesResult)) {
                echo '<option value="' . $course['coursenum'] . '">' . $course['coursenum'] . ' - ' . $course['coursename'] . '</option>';
            }
            echo '</select>';
        }
        ?>

	<h2>Assign Courses Worked On:</h2>
	<label for="assignedCourse">Select Course:</label>
        <select name="assignedCourse" id="assignedCourse">
        <option value="">No Course Selected</option>
        <?php
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
	
	<br>
	<label for="hoursWorked">Hours Worked:</label>
        <input type="number" name="hoursWorked" id="hoursWorked" min="0">
	<br>
	<br>  
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

        $assignedCoid = isset($_POST["assignedCourse"]) ? mysqli_real_escape_string($connection, $_POST["assignedCourse"]) : null;
        $hoursWorked = isset($_POST["hoursWorked"]) ? mysqli_real_escape_string($connection, $_POST["hoursWorked"]) : null;

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            echo "A TA with the same User ID or Student Number already exists.";
        } else {
            $addQuery = "INSERT INTO ta (tauserid, firstname, lastname, studentnum, degreetype) VALUES ('$tauserid', '$firstname', '$lastname', '$studentnumber', '$degreetype')";
            
            if (mysqli_query($connection, $addQuery)) {
                echo "New TA added successfully.";
		if (isset($_POST['lovedCourses'])) {
                foreach ($_POST['lovedCourses'] as $lovedCourse) {
                    $lovedCourse = mysqli_real_escape_string($connection, $lovedCourse);
                    $insertLoved = "INSERT INTO loves (ltauserid, lcoursenum) VALUES ('$tauserid', '$lovedCourse')";
                    mysqli_query($connection, $insertLoved);
                }
            }
            if (isset($_POST['hatedCourses'])) {
                foreach ($_POST['hatedCourses'] as $hatedCourse) {
                    $hatedCourse = mysqli_real_escape_string($connection, $hatedCourse);
                    $insertHated = "INSERT INTO hates (htauserid, hcoursenum) VALUES ('$tauserid', '$hatedCourse')";
                    mysqli_query($connection, $insertHated);
                }
            }
            
            if (!empty($assignedCoid) && !empty($hoursWorked)) {
                $assignCourseQuery = "INSERT INTO hasworkedon (tauserid, coid, hours) VALUES ('$tauserid', '$assignedCoid', '$hoursWorked')";
                if (mysqli_query($connection, $assignCourseQuery)) {
                    echo "\n Assigned course and hours worked updated successfully.<br>";
                } else {
                    echo "Error assigning course: " . mysqli_error($connection);
                }
            }
        } else {
            echo "Error adding new TA: " . mysqli_error($connection);
        }
    }
}
    mysqli_close($connection);
    ?>
</div>
</body>
</html>
