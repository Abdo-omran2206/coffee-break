<?php

$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['status']) && $_POST['status'] === 'editCustomerStatus') {
    $key = $_POST["key"];
    $newStatus = $_POST['newStatus']; // Assuming 'newStatus' is passed in the POST request
    
    // Prepare MySQLi statement with ? placeholders
    $stmt = $db->prepare('UPDATE casher SET state = ? WHERE random_key = ?');
    $stmt->bind_param('ss', $newStatus, $key);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Customer status updated successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update customer status'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}

$db->close();
?>