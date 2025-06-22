<?php
header('Content-Type: application/json');

try {
    // Fix MySQL connection for Windows
    $db = new mysqli('localhost', 'root', '', 'coffee-break');
    
    // Check connection
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }
    
    // Get time range from request
    $timeRange = isset($_POST['timeRange']) ? $_POST['timeRange'] : 'month';
    
    // Set the date range based on selection
    switch($timeRange) {
        case 'today':
            $startDate = date('Y-m-d 00:00:00');
            $previousStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
            break;
        case 'week':
            $startDate = date('Y-m-d 00:00:00', strtotime('-6 days'));
            $previousStart = date('Y-m-d 00:00:00', strtotime('-13 days'));
            break;
        case 'year':
            $startDate = date('Y-m-d 00:00:00', strtotime('-365 days'));
            $previousStart = date('Y-m-d 00:00:00', strtotime('-730 days'));
            break;
        default: // month
            $startDate = date('Y-m-d 00:00:00', strtotime('-29 days'));
            $previousStart = date('Y-m-d 00:00:00', strtotime('-59 days'));
    }

    $endDate = date('Y-m-d H:i:s');
    $previousEnd = $startDate;

    // Current Period Metrics
    $currentMetrics = $db->prepare("
        SELECT 
            COUNT(*) as totalOrders,
            SUM(full_price) as totalRevenue,
            COUNT(DISTINCT username) as totalCustomers,
            AVG(full_price) as averageOrderValue,
            COUNT(CASE WHEN state = 'done' THEN 1 END) as completedOrders
        FROM casher 
        WHERE created_at BETWEEN ? AND ?
    ");
    
    $currentMetrics->bind_param('ss', $startDate, $endDate);
    $currentMetrics->execute();
    $current = $currentMetrics->get_result()->fetch_assoc();

    // Previous Period Metrics
    $previousMetrics = $db->prepare("
        SELECT 
            COUNT(*) as totalOrders,
            SUM(full_price) as totalRevenue,
            COUNT(DISTINCT username) as totalCustomers,
            AVG(full_price) as averageOrderValue
        FROM casher 
        WHERE created_at BETWEEN ? AND ?
    ");
    
    $previousMetrics->bind_param('ss', $previousStart, $previousEnd);
    $previousMetrics->execute();
    $previous = $previousMetrics->get_result()->fetch_assoc();

    // Get Revenue Data for Chart
    $revenueData = $db->prepare("
        SELECT 
            DATE(created_at) as date,
            SUM(full_price) as daily_revenue,
            COUNT(*) as daily_orders
        FROM casher 
        WHERE created_at BETWEEN ? AND ?
        GROUP BY DATE(created_at)
        ORDER BY date
    ");
    
    $revenueData->bind_param('ss', $startDate, $endDate);
    $revenueData->execute();
    $result = $revenueData->get_result();
    
    $revenueByDay = [];
    while ($row = $result->fetch_assoc()) {
        $revenueByDay[] = $row;
    }

    // Get Customer Distribution Data
    $customerData = $db->prepare("
        SELECT 
            state,
            COUNT(*) as count
        FROM casher 
        WHERE created_at BETWEEN ? AND ?
        GROUP BY state
    ");
    
    $customerData->bind_param('ss', $startDate, $endDate);
    $customerData->execute();
    $result = $customerData->get_result();
    
    $customerDistribution = [];
    while ($row = $result->fetch_assoc()) {
        $customerDistribution[] = $row;
    }

    // Calculate New Customers
    $newCustomers = $db->prepare("
        SELECT COUNT(DISTINCT c1.username) as new_customers
        FROM casher c1
        WHERE c1.created_at BETWEEN ? AND ?
        AND NOT EXISTS (
            SELECT 1 FROM casher c2
            WHERE c2.username = c1.username
            AND c2.created_at < ?
        )
    ");
    
    $newCustomers->bind_param('sss', $startDate, $endDate, $startDate);
    $newCustomers->execute();
    $newCustomerResult = $newCustomers->get_result()->fetch_assoc();
    $newCustomerCount = $newCustomerResult['new_customers'];

    // Calculate Retention Rate
    $retentionRate = 0;
    if ($previous['totalCustomers'] > 0) {
        $returningCustomers = $db->prepare("
            SELECT COUNT(DISTINCT c1.username) as returning
            FROM casher c1
            WHERE c1.created_at BETWEEN ? AND ?
            AND EXISTS (
                SELECT 1 FROM casher c2
                WHERE c2.username = c1.username
                AND c2.created_at BETWEEN ? AND ?
            )
        ");
        
        $returningCustomers->bind_param('ssss', $startDate, $endDate, $previousStart, $previousEnd);
        $returningCustomers->execute();
        $returningResult = $returningCustomers->get_result()->fetch_assoc();
        $returning = $returningResult['returning'];
        
        $retentionRate = ($returning / $previous['totalCustomers']) * 100;
    }

    // Prepare Chart Data
    $labels = [];
    $revenues = [];
    $orders = [];
    foreach ($revenueByDay as $day) {
        $labels[] = date('M d', strtotime($day['date']));
        $revenues[] = floatval($day['daily_revenue']);
        $orders[] = intval($day['daily_orders']);
    }

    // Calculate Growth Rates
    $revenueGrowth = $previous['totalRevenue'] > 0 ? 
        (($current['totalRevenue'] - $previous['totalRevenue']) / $previous['totalRevenue']) * 100 : 0;

    // Prepare Response Data
    $response = [
        'success' => true,
        'data' => [
            'totalRevenue' => $current['totalRevenue'] ?? 0,
            'totalOrders' => $current['totalOrders'] ?? 0,
            'averageOrderValue' => $current['averageOrderValue'] ?? 0,
            'newCustomers' => $newCustomerCount ?? 0,
            'retentionRate' => round($retentionRate, 1),
            'revenueGrowth' => round($revenueGrowth, 1),
            'previousRevenue' => $previous['totalRevenue'] ?? 0,
            'previousOrders' => $previous['totalOrders'] ?? 0,
            'previousAverageOrderValue' => $previous['averageOrderValue'] ?? 0,
            'revenueData' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Revenue',
                        'data' => $revenues,
                        'borderColor' => '#7D5A50',
                        'backgroundColor' => 'rgba(125, 90, 80, 0.1)',
                        'fill' => true
                    ]
                ]
            ],
            'ordersData' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Orders',
                        'data' => $orders,
                        'backgroundColor' => '#B4846C'
                    ]
                ]
            ],
            'customersData' => [
                'labels' => array_column($customerDistribution, 'state'),
                'datasets' => [
                    [
                        'data' => array_column($customerDistribution, 'count'),
                        'backgroundColor' => [
                            '#7D5A50',
                            '#B4846C',
                            '#E5B299',
                            '#FCDEC0'
                        ]
                    ]
                ]
            ]
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>