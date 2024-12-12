
<?php
try {
   $serverName = getenv('a4.database.windows.net');
$database = getenv('a4');
$username = getenv('A4');
$password = getenv('Test1234!');

 // Attempt to connect
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo json_encode(["status" => "success", "message" => "Database connected"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
