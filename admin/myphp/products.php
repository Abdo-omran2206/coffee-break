<?php
header('Content-Type: application/json');

try {
    // Connect to the drinks database
    $db = new mysqli('localhost', 'root', '', 'coffee-break');
    
    if ($db->connect_error) {
        throw new Exception("Failed to connect to the database: " .  $db->connect_error);
    }
    $tableQuery = "CREATE TABLE IF NOT EXISTS drinks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        image VARCHAR(255)
    )";
    
    if (!$db->query($tableQuery)) {
        throw new Exception("Failed to create table: " . $db->error);
    }
    // Get all products
    $query = "SELECT * FROM drinks ORDER BY id";
    $result = $db->query($query);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['image']
        ];
    }

    // Return the products as JSON
    echo json_encode([
        'status' => 'success',
        'products' => $products
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($db)) {
        $db->close();
    }
}
?> 