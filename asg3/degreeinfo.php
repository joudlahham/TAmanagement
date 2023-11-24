<?php require_once('dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Degree Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a href="mainmenu.php">Back to Menu</a>
    <h2>Degree Information</h2>

    <form action="degreeinfo.php" method="get">
        <input type="radio" name="sort" value="lastname_asc" id="lastname_asc">
        <label for="lastname_asc">Last Name Ascending</label><br>
        <input type="radio" name="sort" value="lastname_desc" id="lastname_desc">
        <label for="lastname_desc">Last Name Descending</label><br>
        <input type="radio" name="sort" value="degreetype" id="degreetype">
        <label for="degreetype">Degree Type</label><br>
        <input type="submit" value="Sort">
    </form>

    <?php
    $sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'lastname_asc';
    switch ($sortOption) {
        case 'lastname_asc':
            $orderBy = 'lastname ASC';
            break;
        case 'lastname_desc':
            $orderBy = 'lastname DESC';
            break;
        case 'degreetype':
            $orderBy = 'degreetype, lastname ASC';
            break;
        default:
            $orderBy = 'lastname ASC';
    }

    $query = "SELECT * FROM ta ORDER BY $orderBy";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Database query failed.");
    }

    while($ta = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<p>" . $ta['firstname'] . " " . $ta['lastname'] . " - " . $ta['degreetype'] . "</p>";
        echo "</div>";
    }

    mysqli_free_result($result);
    ?>

</body>
</html>
<?php mysqli_close($connection); ?>
