<?php

$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

if($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === 'getOrders'){
    // Use MySQL CURDATE() for today's date
    $stmt = $db->prepare('SELECT * FROM casher WHERE DATE(created_at) = CURDATE()');
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        'status'=> 'success',
        'data'=> $data
    ]);
} else {
    echo json_encode([
        'status'=> 'error',
        'message'=> 'Invalid request'
    ]);
}

$db->close();
?>