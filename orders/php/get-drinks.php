<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');;
    
    $query = isset($_GET['search']) ? 
        "SELECT * FROM drinks WHERE name LIKE :search" : 
        "SELECT * FROM drinks";
    
    $stmt = $db->prepare($query);
    
    if (isset($_GET['search'])) {
        $search = '%' . $_GET['search'] . '%';
        $stmt->bind_param('ss',$search, $search);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $drinks = [];
    
    while ($row = $result->fetch_assoc()) {
        $drinks[] = $row;
    }
    
    echo json_encode($drinks);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($db)) {
        $db->close();
    }
}
?> 