<?php
try {
    // Establish the database connection
    $conn = new PDO("sqlsrv:server = tcp:a4.database.windows.net; Database = a4", 
                    "A4", 
                    "Test1234!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize action and ID (if provided)
    $action = isset($_GET['action']) ? $_GET['action'] : null;
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($action === 'read') {
        // Fetch all patient records
        $stmt = $conn->prepare("SELECT * FROM Patients");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data = $stmt->fetchAll();
    } elseif ($action === 'delete' && $id) {
        // Delete a patient by ID
        $stmt = $conn->prepare("DELETE FROM Patients WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<p>Record with ID $id has been deleted successfully.</p>";
        $data = [];
    } else {
        // Default to an empty array if no action is specified
        $data = [];
    }
} catch (PDOException $e) {
    // Handle connection errors
    print("Error connecting to SQL Server.");
    die(print_r($e));
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Database Demonstration</title>
    <style>
      table, td, th {
        border: 1px solid black;
      }
      td, th {
        padding: 5px;
      }
      table {
        border-collapse: collapse;
      }
    </style>
  </head>
  <body>

    <h1>Database Example</h1>

    <?php if ($action === 'read' || !$action): ?>
      <table>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
        </tr>
        <?php 
        foreach ($data as $record):
        ?>
        <tr>
          <td><?php echo htmlspecialchars($record["id"]); ?></td>
          <td><?php echo htmlspecialchars($record["firstname"]); ?></td>
          <td><?php echo htmlspecialchars($record["lastname"]); ?></td>
        </tr>
        <?php 
        endforeach; 
        ?>
      </table>
    <?php elseif ($action === 'delete'): ?>
      <p>Deleted record with ID: <?php echo htmlspecialchars($id); ?></p>
    <?php else: ?>
      <p>No action performed.</p>
    <?php endif; ?>

  </body>
</html>
