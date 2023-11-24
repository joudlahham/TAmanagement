<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Courses and Offerings</title>
</head>
<body>
    <?php include 'connectdb.php'; ?>

    <h1>All Courses and Their Offerings</h1>
    
    <!-- Form for selecting a specific course -->
    <form action="courses.php" method="post">
        Select a course: 
        <select name="selectedCourse">
            <?php
            $query = "SELECT DISTINCT coursenum, coursename FROM course";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Database query failed.");
            }
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['coursenum'] . "'>" . $row['coursenum'] . " - " . $row['coursename'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="View Offerings">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedCourse = $_POST["selectedCourse"];

        echo "<h2>Offerings for Course: $selectedCourse</h2>";

        $courseQuery = "SELECT * FROM courseoffer WHERE whichcourse='$selectedCourse'";
        $courseResult = mysqli_query($connection, $courseQuery);
        if (!$courseResult) {
            die("Database query failed.");
        }
        if (mysqli_num_rows($courseResult) > 0) {
            echo "<table>";
            echo "<tr><th>Offering ID</th><th>Student Count</th><th>Term</th><th>Year</th></tr>";
            while ($courseRow = mysqli_fetch_assoc($courseResult)) {
                echo "<tr>";
                echo "<td>" . $courseRow['coid'] . "</td>";
                echo "<td>" . $courseRow['numstudent'] . "</td>";
                echo "<td>" . $courseRow['term'] . "</td>";
                echo "<td>" . $courseRow['year'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No offerings found for this course.";
        }
    }

    mysqli_close($connection);
    ?>
</body>
</html>
