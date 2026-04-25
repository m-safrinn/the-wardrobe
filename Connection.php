<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = ''; // Add your database password here
$dbname = 'wardrobe';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>