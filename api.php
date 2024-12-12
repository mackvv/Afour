<?php
$host = 'a4.database.windows.net';
$username = 'A4';
$password = 'Test1234!';
$db_name = 'a4';

//Establishes the connection
$conn = mysqli_init();
mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);
if (mysqli_connect_errno($conn)) {
die('Failed to connect to MySQL: '.mysqli_connect_error());
}


//Close the connection
mysqli_close($conn);
?>
