<?php
session_start();
header('Content-Type: application/json');

// Database connection using socket
$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

// Create necessary tables if they don't exist
// Create the table if it doesn't exist
$db->query('CREATE TABLE IF NOT EXISTS casher (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    cartorder TEXT,
    full_price DECIMAL(10,2),
    random_key VARCHAR(255),
    state VARCHAR(50),
    created_at DATETIME
)');

// Handle different operations
$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_users':
        $stmt = $db->prepare('SELECT * FROM casher');
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['success' => true, 'users' => $users]);
        break;

    case 'update_mode':
        $user_id = $_GET['user_id'] ?? '';
        $new_mode = $_GET['status'] ?? '';
        
        if($user_id && $new_mode) {
            $stmt = $db->prepare('UPDATE casher SET state = ? WHERE random_key = ?');
            $stmt->bind_param('ss', $new_mode, $user_id);
            
            if($stmt->execute()) {
                echo json_encode(['success' => true , 'message' => 'User Status updated']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update mode']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing parameters']);
        }
        break;


    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

$db->close();
?>