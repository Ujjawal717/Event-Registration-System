<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_form_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Get total registrations
$totalQuery = "SELECT COUNT(*) as total FROM registrations";
$totalResult = $conn->query($totalQuery);
$total = $totalResult->fetch_assoc()['total'];

// Get today's registrations
$todayQuery = "SELECT COUNT(*) as today FROM registrations WHERE DATE(created_at) = CURDATE()";
$todayResult = $conn->query($todayQuery);
$today = $todayResult->fetch_assoc()['today'];

// Get recent registrations (last 10)
$recentQuery = "SELECT id, name, email, event, created_at FROM registrations ORDER BY created_at DESC LIMIT 10";
$recentResult = $conn->query($recentQuery);
$recent = [];
while ($row = $recentResult->fetch_assoc()) {
    $recent[] = $row;
}

// Get daily trend data (last 7 days)
$trendQuery = "SELECT DATE(created_at) as date, COUNT(*) as count FROM registrations WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY date";
$trendResult = $conn->query($trendQuery);
$trendData = [];
while ($row = $trendResult->fetch_assoc()) {
    $trendData[] = $row;
}

$conn->close();

echo json_encode([
    'total' => $total,
    'today' => $today,
    'recent' => $recent,
    'trend' => $trendData
]);
?>