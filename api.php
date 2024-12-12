<?php
//"StAuth10222: I Mackenzie Van Vliet,000860031 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else."

$dbHost = getenv('DB_HOST');       // Example: yourserver.database.windows.net
$dbName = getenv('DB_NAME');       // Your database name
$dbUser = getenv('DB_USER');       // Your database user
$dbPass = getenv('DB_PASS');       // Your database password

try {
    $dsn = "sqlsrv:server=$dbHost;database=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    $action = $_GET['action'] ?? null;

    if ($action === 'read') {
        $stmt = $pdo->query("SELECT * FROM Patients");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($patients);

    } elseif ($action === 'delete' && isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM Patients WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode(['message' => "Record ID:$id deleted"]);

    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
