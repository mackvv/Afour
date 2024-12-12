<?php
// Use environment variables or secure configuration for credentials
$host = getenv('DB_HOST') ?: 'a4.database.windows.net';
$username = getenv('DB_USER') ?: 'A4';
$password = getenv('DB_PASS') ?: 'Test1234!';
$db_name = getenv('DB_NAME') ?: 'a4';

// Establish the connection
$conn = mysqli_init();
if (!mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306)) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Run the SELECT query
echo "Reading data from table:\n";
$query = 'SELECT * FROM Patients';
$res = mysqli_query($conn, $query);

if ($res) {
    echo "<pre>";
    while ($row = mysqli_fetch_assoc($res)) {
        print_r($row);
    }
    echo "</pre>";
} else {
    die('Query failed: ' . mysqli_error($conn));
}

// Close the connection
mysqli_close($conn);
?>
