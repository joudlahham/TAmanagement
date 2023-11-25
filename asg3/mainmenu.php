<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
	    
            display: flex;
	    flex-direction: column; 
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
	.title {
            margin-top: 60px;
            font-size: 2em;
            color: #333;
        }
        .menu {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Three items per row */
            grid-gap: 50px; 
            max-width: 600px;
            margin: auto; 
        }
        .menu-item {
            background-color:#6548B7;
            color: white;
	    width: 140px;
            height: 140px;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .menu-item:hover {
            background-color: #5235A5;
        }
        @media (max-width: 600px) {
            .menu {
                grid-template-columns: 1fr; /* One item per row on smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="title">TA Management System</div>
    <div class="menu">
        <a href="tainfo.php" class="menu-item">TA Information</a>
        <a href="courses.php" class="menu-item">Course Offerings</a>
        <a href="addta.php" class="menu-item">Add TA</a>
        <a href="deleteta.php" class="menu-item">Delete TA</a>
        <a href="modifyta.php" class="menu-item">Modify TA</a>
    </div>
</body>
</html>
