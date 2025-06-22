<?php

$db = new mysqli('localhost', 'root', '', 'coffee-break');


if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === 'cancel'){
    $kay = $_POST['random_key'];
    $stmt = $db->prepare('UPDATE casher SET state = ? WHERE random_key = ?');
    $state = 'cancelled';
    $stmt->bind_param('ss', $state, $kay);
    if($stmt->execute()){
        echo json_encode([
            "status" => "success",
            "message" => "Order cancelled successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to cancel order."
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['action'] === 'get_old'){
    $kay = $_POST['random_key'];
    $stmt = $db->prepare('SELECT * FROM casher WHERE random_key = ?');
    $stmt->bind_param('s', $kay);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    echo json_encode([
        'status'=> 'success',
        'data'=> $data
        ]);
}

?>