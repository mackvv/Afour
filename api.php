<?php
// Load environment variables
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve environment variables
$dbHost = getenv('DB_HOST');       // Example: yourserver.database.windows.net
$dbName = getenv('DB_NAME');       // Your database name
$dbUser = getenv('DB_USER');       // Your database user
$dbPass = getenv('DB_PASS');       // Your database password

try {
    // Establish database connection
    $dsn = "sqlsrv:server=$dbHost;database=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle the action parameter
    $action = $_GET['action'] ?? null;

    if ($action === 'read') {
        // Fetch all patient records
        $stmt = $pdo->query("SELECT * FROM Patients");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output records as JSON
        header('Content-Type: application/json');
        echo json_encode($patients);

    } elseif ($action === 'delete' && isset($_GET['id'])) {
        // Delete a patient record
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM Patients WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Output success message
        header('Content-Type: application/json');
        echo json_encode(['message' => "Record with id $id deleted successfully"]);

    } else {
        // Invalid action
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
