
<?php
try {
    $conn = new PDO("sqlsrv:server = tcp:a4.database.windows.net; Database = a4", 
                    "A4", 
                    "Test1234!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Persons");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $data = $stmt->fetchAll();
     
    // Dump the data to the page
    // print_r($data);
    
}
catch (PDOException $e) {
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

    <table>
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
      </tr>
      <?php 

        foreach ($data as $record)
        {
          ?>
          <tr>
            <td><?php echo $record["id"]?></td>
            <td><?php echo $record["firstname"]?></td>
            <td><?php echo $record["lastname"]?></td>
          </tr>
          <?
        }

      ?>
    </table>

  </body>
</html>
