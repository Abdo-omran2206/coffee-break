<?php

// MySQLi connection (no socket for Windows)
$db = new mysqli('localhost', 'root', '', 'coffee-break');
$drinks_db = new mysqli('localhost', 'root', '', 'coffee-break');

if ($db->connect_error || $drinks_db->connect_error) {
    die("Failed to connect to the database: " . $db->connect_error . ' ' . $drinks_db->connect_error);
}

function getdata($db){
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM casher WHERE DATE(created_at) = CURDATE() AND state != 'cancelled'");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

function getcustomers($db){
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM casher");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

function gettodaycash($db){
    $stmt = $db->prepare("SELECT full_price FROM casher WHERE DATE(created_at) = CURDATE() AND state != 'cancelled'");
    $stmt->execute();
    $result = $stmt->get_result();
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $total += $row['full_price'];
    }
    $stmt->close();
    return $total;
}

function getproduct($drinks_db){
    $stmt = $drinks_db->prepare("SELECT COUNT(*) as count FROM drinks");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data['count'];
}

echo json_encode([
    'status' => 'success',
    'todayOrders' => getdata($db),
    'customers' => getcustomers($db),
    'todaycash' => gettodaycash($db),
    'products'=> getproduct($drinks_db),
]);

// Close database connections
$db->close();
$drinks_db->close();

?>