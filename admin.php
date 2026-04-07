<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_form_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total registrations
$totalQuery = "SELECT COUNT(*) as total FROM registrations";
$totalResult = $conn->query($totalQuery);
$total = $totalResult->fetch_assoc()['total'];

// Get today's registrations
$todayQuery = "SELECT COUNT(*) as today FROM registrations WHERE DATE(created_at) = CURDATE()";
$todayResult = $conn->query($todayQuery);
$today = $todayResult->fetch_assoc()['today'];

// Get registrations by event
$eventQuery = "SELECT event, COUNT(*) as count FROM registrations GROUP BY event ORDER BY count DESC";
$eventResult = $conn->query($eventQuery);
$events = [];
while ($row = $eventResult->fetch_assoc()) {
    $events[] = $row;
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Participation</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
        }

        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .section h3 {
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #667eea;
            color: white;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard - Student Participation</h1>
            <p>Live monitoring and trend analysis</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number" id="total-reg"><?php echo $total; ?></div>
                <div class="stat-label">Total Registrations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="today-reg"><?php echo $today; ?></div>
                <div class="stat-label">Today's Registrations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($events); ?></div>
                <div class="stat-label">Active Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total > 0 ? round(($today / $total) * 100, 1) : 0; ?>%</div>
                <div class="stat-label">Today's Share</div>
            </div>
        </div>

        <div class="content">
            <div class="section">
                <h3>Recent Registrations</h3>
                <table id="recent-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Event</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $reg): ?>
                        <tr>
                            <td><?php echo $reg['id']; ?></td>
                            <td><?php echo htmlspecialchars($reg['name']); ?></td>
                            <td><?php echo htmlspecialchars($reg['email']); ?></td>
                            <td><?php echo htmlspecialchars($reg['event']); ?></td>
                            <td><?php echo date('H:i:s', strtotime($reg['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h3>Event Popularity</h3>
                <div class="chart-container">
                    <canvas id="eventChart"></canvas>
                </div>
            </div>

            <div class="section">
                <h3>Registration Trends (Last 7 Days)</h3>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="section">
                <h3>Event Statistics</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Registrations</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['event']); ?></td>
                            <td><?php echo $event['count']; ?></td>
                            <td><?php echo $total > 0 ? round(($event['count'] / $total) * 100, 1) : 0; ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Event Popularity Chart
        const eventCtx = document.getElementById('eventChart').getContext('2d');
        const eventData = <?php echo json_encode($events); ?>;
        const eventChart = new Chart(eventCtx, {
            type: 'doughnut',
            data: {
                labels: eventData.map(item => item.event),
                datasets: [{
                    data: eventData.map(item => item.count),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = <?php echo json_encode($trendData); ?>;
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.map(item => new Date(item.date).toLocaleDateString()),
                datasets: [{
                    label: 'Daily Registrations',
                    data: trendData.map(item => item.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Live updates every 30 seconds
        function updateLiveData() {
            fetch('admin_data.php')
                .then(response => response.json())
                .then(data => {
                    // Update stats
                    document.getElementById('total-reg').textContent = data.total;
                    document.getElementById('today-reg').textContent = data.today;

                    // Update recent table
                    const tbody = document.querySelector('#recent-table tbody');
                    tbody.innerHTML = '';
                    data.recent.forEach(reg => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${reg.id}</td>
                            <td>${reg.name}</td>
                            <td>${reg.email}</td>
                            <td>${reg.event}</td>
                            <td>${new Date(reg.created_at).toLocaleTimeString()}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Update trend chart
                    trendChart.data.labels = data.trend.map(item => new Date(item.date).toLocaleDateString());
                    trendChart.data.datasets[0].data = data.trend.map(item => item.count);
                    trendChart.update();
                })
                .catch(error => console.error('Error updating data:', error));
        }

        // Update every 30 seconds
        setInterval(updateLiveData, 30000);
    </script>
</body>
</html>