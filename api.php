<?php
// Fetch environment variables
$serverName = getenv('a4.database.windows.net'); // e.g., 'a4.database.windows.net'
$database = getenv('a4');     // Your database name
$username = getenv('A4');     // SQL admin username
$password = getenv('Test1234!'); // SQL admin password

// Establish database connection
try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password, [
        PDO::SQLSRV_ATTR_ENCRYPT => 1, // Enforce SSL connection
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $e->getMessage()]);
    exit;
}

// Handle HTTP GET requests
$action = $_GET['action'] ?? null;

if ($action === 'read') {
    // Fetch all records from the Patients table
    try {
        $stmt = $conn->query("SELECT * FROM Patients");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($patients);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        // Delete a record with the given ID
        try {
            $stmt = $conn->prepare("DELETE FROM Patients WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo json_encode(["status" => "success", "message" => "Record with ID $id deleted."]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing 'id' parameter."]);
    }
} else {
    // Invalid action
    echo json_encode(["status" => "error", "message" => "Invalid action. Use 'read' or 'delete'."]);
}
?>
