<?php

// Set header to return JSON response
header('Content-Type: application/json');

$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

if($_SERVER['REQUEST_METHOD'] === "POST") {
    if(isset($_POST['action']) && $_POST['action'] === 'customer') {
        $key = $_POST['key'];
        $stmt = $db->prepare("DELETE FROM casher WHERE random_key = ?");
        $stmt->bind_param('s', $key);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Customer deleted successfully']);
    }
}

?> 