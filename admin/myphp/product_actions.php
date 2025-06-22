<?php
header('Content-Type: application/json');

try {
    // Connect to the drinks database
    $db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');
    
    if ($db->connect_error) {
        throw new Exception("Failed to connect to the database: " . $db->connect_error);
    }

    // Get the action from POST data
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            // Find the lowest available (free) id
            $result = $db->query('SELECT id FROM drinks ORDER BY id ASC');
            $free_id = 1;
            if ($result) {
                $ids = [];
                while ($row = $result->fetch_assoc()) {
                    $ids[] = (int)$row['id'];
                }
                $result->free();
                // Find the first missing id in the sequence
                foreach ($ids as $id) {
                    if ($id == $free_id) {
                        $free_id++;
                    } else {
                        break;
                    }
                }
            }

            // Add new product with free id
            $stmt = $db->prepare('INSERT INTO drinks (id, name, price, description, image) VALUES (?, ?, ?, ?, ?)');
            $name = $_POST['name'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $image = $_POST['image'] ?? '';
            $stmt->bind_param('isdss', $free_id, $name, $price, $description, $image);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Product added successfully', 'id' => $free_id]);
            } else {
                throw new Exception("Failed to add product");
            }
            break;

        case 'edit':
            // Edit existing product
            $stmt = $db->prepare('UPDATE drinks SET name = ?, price = ?, description = ?, image = ? WHERE id = ?');
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $image = $_POST['image'] ?? '';
            $stmt->bind_param('sdssi', $name, $price, $description, $image, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
            } else {
                throw new Exception("Failed to update product");
            }
            break;

        case 'delete':
            // Delete product
            $stmt = $db->prepare('DELETE FROM drinks WHERE id = ?');
            $id = $_POST['id'];
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
            } else {
                throw new Exception("Failed to delete product");
            }
            break;

        default:
            throw new Exception("Invalid action specified");
    }

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