<?php
header('Content-Type: application/json');

try {
    $conn = new PDO("sqlsrv:server = tcp:a4.database.windows.net; Database = a4", 
                    "A4", 
                    "Test1234!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($action === 'read') {
        $stmt = $conn->prepare("SELECT * FROM Patients");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data = $stmt->fetchAll();
        echo json_encode($data);
    } elseif ($action === 'delete' && $id) {
        $stmt = $conn->prepare("DELETE FROM Patients WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["message" => "Record with ID $id has been deleted successfully."]);
    } else {
        echo json_encode(["error" => "Invalid action. Use 'read' or 'delete'."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database connection failed.", "details" => $e->getMessage()]);
}
?>
