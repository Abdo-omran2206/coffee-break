<?php
$db = new mysqli('localhost', 'root', '', 'coffee-break', null, '/var/run/mysqld/mysqld.sock');

// Create the table if it doesn't exist (MySQLi syntax)
$db->query('CREATE TABLE IF NOT EXISTS casher (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    cartorder TEXT,
    full_price DECIMAL(10,2),
    random_key VARCHAR(255),
    state VARCHAR(50),
    created_at DATETIME
)');

if($_SERVER['REQUEST_METHOD'] === "POST") {
    if(isset($_POST['status']) && $_POST['status'] === 'customers') {
        $stmt = $db->prepare('SELECT * FROM casher ORDER BY id DESC LIMIT 5');
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
    } 
    else if(isset($_POST['get_Recent_Orders'])) {
        $stmt = $db->prepare('SELECT * FROM casher ORDER BY id DESC LIMIT 5');
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'order_id' => $row['random_key'],
                'customer_name' => $row['username'],
                'items_count' => count(explode(',', $row['cartorder'])),
                'total' => $row['full_price'],
                'status' => $row['state'],
                'state' => $row['state'],
                'created_at' => $row['created_at'],
                'cartorder' => $row['cartorder'],
                'random_key' => $row['random_key']
            ];
        }

        echo json_encode($data);
    }
}
$db->close();
?>