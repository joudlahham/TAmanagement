<!-- Programmer: Joud Al-lahham 
     Student Number: 82
     Date: 2023/11/25
     File: dbconnect.php
     Description: This file connects to the database built for this assignment.
-->

<?php
$dbhost = "localhost";
$dbuser= "root";
$dbpass = "Joudy12.";
$dbname = "assign2db";
$connection = mysqli_connect($dbhost, $dbuser,$dbpass,$dbname);
if (mysqli_connect_errno()) {
 die("Database connection failed :" .
 mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
 } //end of if statement
?>
