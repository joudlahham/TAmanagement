// Programmer Name: Joud Al-lahham
// 82
// This is a simple HTML file that presents the user with a list of actions they can perform, such as viewing TA information, degree information, and options to insert, delete, or modify TA records.

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .menu {
            width: 200px;
            margin: 30px auto;
        }
        .menu-item {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            display: block;
        }
        .menu-item:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="menu">
        <a href="tainfo.php" class="menu-item">TA Information</a>
        <a href="degreeinfo.php" class="menu-item">Degree Information</a>
	<a href="addta.php" class="menu-item">Add TA</a>
	<a href="deleteta.php" class="menu-item">Delete TA</a>
	<a href="modifyta.php" class="menu-item"Modify TA</a>
    </div>
</body>
</html>
