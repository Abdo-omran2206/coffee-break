<?php

$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

if($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === 'getCustomers'){
    $stmt = $db->prepare('SELECT username, full_price, random_key, created_at, state FROM casher');
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

?>