<?php
// Database connection using environment variables
$serverName = getenv('a4.database.windows.net'); // Example: 'a4.database.windows.net'
$username = getenv('A4');  // Example: 'A4'
$password = getenv('Test1234!'); // Example: 'Test1234!'
$database = getenv('A4');  // Example: 'A4'

try {
    // Establish database connection
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::SQLSRV_ATTR_ENCRYPT => true // Enforce encrypted connection
    ]);
} catch (PDOException $e) {
    // Return error response if the database connection fails
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Retrieve the action parameter
$action = $_GET['action'] ?? null;

// Handle the different actions
if ($action === 'read') {
    // Fetch all records from the Patients table
    try {
        $stmt = $conn->query("SELECT * FROM Patients");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the records as a JSON response
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "data" => $patients]);
    } catch (PDOException $e) {
        // Handle SQL errors
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error fetching records: " . $e->getMessage()]);
    }
} elseif ($action === 'delete') {
    // Get the ID to delete
    $id = $_GET['id'] ?? null;

    if ($id) {
        try {
            // Prepare and execute the DELETE query
            $stmt = $conn->prepare("DELETE FROM Patients WHERE id = :id");
            $stmt->execute(['id' => $id]);

            // Return success response
            echo json_encode(["status" => "success", "message" => "Record with ID $id deleted."]);
        } catch (PDOException $e) {
            // Handle SQL errors
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error deleting record: " . $e->getMessage()]);
        }
    } else {
        // Handle missing ID parameter
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing 'id' parameter."]);
    }
} else {
    // Handle invalid or missing action parameter
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid action. Use 'read' or 'delete'."]);
}

// Close the database connection
$conn = null;
?>
