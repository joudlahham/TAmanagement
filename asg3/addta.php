<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: addta.php
     Description: This file adds a TA to the ta database
-->

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
	<!-- Form for adding a new TA -->
        <form action="addta.php" method="post">
            <p> TA User ID: <input type="text" name="userid" required></p>
            <p> First Name: <input type="text" name="firstname" required></p>
            <p> Last Name: <input type="text" name="lastname" required></p>
            <p> Student Number: <input type="text" name="studentnumber" required></p>
 	<label for="taImageUrl">TA Image URL:</label>
        <input type="text" name="taImageUrl" id="taImageUrl">
	    <p> Degree Type:
    		<select name="degreetype" required>
        	<option value="Masters">Masters</option>
        	<option value="PhD">PhD</option>
    		</select>
	     </p>
        
	<!-- Dropdown to select courses the new TA loves -->
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
	
        <!-- Dropdown to select courses the new TA hates -->
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

        <!-- Dropdown to assign courses the new TA has worked on -->
	<h2>Assign Courses Worked On:</h2>
	<label for="assignedCourse">Select Course:</label>
        <select name="assignedCourse" id="assignedCourse">
        <option value="">No Course Selected</option>
        <?php
        // Query to get course offerings for the assignment
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

        <!-- Input field for hours worked -->	
	<br>
	<label for="hoursWorked">Hours Worked:</label>
        <input type="number" name="hoursWorked" id="hoursWorked" min="0">
	<br>
	<br>  
	<input type="submit" value="Add TA">
        </form>

    <!-- PHP section to process the form submission -->
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

   
    if (isset($_FILES['taImage'])) {
        $target_dir = "uploads/"; // Directory where you want to save the image
        $target_file = $target_dir . basename($_FILES["taImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["taImage"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["taImage"]["size"] > 500000) { // 500KB size limit
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["taImage"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars(basename($_FILES["taImage"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
    mysqli_close($connection);
    ?>
</div>
</body>
</html>
