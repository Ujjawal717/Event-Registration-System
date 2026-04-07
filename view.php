<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nameQuery = trim($_GET['name'] ?? '');
$collageIdQuery = trim($_GET['collage_id'] ?? '');
$record = null;
$error = '';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_form_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $error = 'Connection failed: ' . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8');
} else {
    if ($id > 0) {
        $stmt = $conn->prepare('SELECT id, name, event, created_at FROM registrations WHERE id = ?');
        $stmt->bind_param('i', $id);
    } elseif ($collageIdQuery !== '') {
        $stmt = $conn->prepare('SELECT id, name, event, created_at FROM registrations WHERE collage_id = ? ORDER BY created_at DESC LIMIT 1');
        $stmt->bind_param('s', $collageIdQuery);
    } elseif ($nameQuery !== '') {
        $searchTerm = '%' . $nameQuery . '%';
        $stmt = $conn->prepare('SELECT id, name, event, created_at FROM registrations WHERE name LIKE ? ORDER BY created_at DESC LIMIT 1');
        $stmt->bind_param('s', $searchTerm);
    } else {
        $stmt = null;
    }

    if ($stmt) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $record = $result->fetch_assoc();
            if (!$record) {
                $error = 'Registration not found. Please check your ID or name and try again.';
            }
            $stmt->close();
        } else {
            $error = 'Query failed: ' . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registration</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 480px;
        }

        .title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 600;
        }

        .message {
            color: #666;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .record-box {
            background: #f7f9ff;
            border: 1px solid #dde3f2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .record-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 15px;
        }

        .record-row span:first-child {
            color: #555;
            font-weight: 600;
        }

        .record-row span:last-child {
            color: #333;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .button:hover {
            opacity: 0.95;
        }

        .error-box {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .link {
            display: inline-block;
            margin-top: 10px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Student Registration Status</h1>
        <p class="message">Enter your registration ID or name to see your saved details.</p>

        <?php if ($error): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($record): ?>
            <div class="record-box">
                <div class="record-row"><span>Registration ID</span><span><?php echo htmlspecialchars($record['id'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                <div class="record-row"><span>Name</span><span><?php echo htmlspecialchars($record['name'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                <div class="record-row"><span>Event</span><span><?php echo htmlspecialchars($record['event'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                <div class="record-row"><span>Registered At</span><span><?php echo htmlspecialchars($record['created_at'], ENT_QUOTES, 'UTF-8'); ?></span></div>
            </div>
            <a class="link" href="form.php">Register for another event</a>
        <?php else: ?>
            <form class="search-form" method="get" action="view.php">
                <input class="search-input" type="text" name="id" placeholder="Enter your registration ID" value="<?php echo $id > 0 ? htmlspecialchars($id, ENT_QUOTES, 'UTF-8') : ''; ?>">
                <input class="search-input" type="text" name="collage_id" placeholder="Enter your collage ID" value="<?php echo htmlspecialchars($collageIdQuery, ENT_QUOTES, 'UTF-8'); ?>">
                <input class="search-input" type="text" name="name" placeholder="Or enter your name" value="<?php echo htmlspecialchars($nameQuery, ENT_QUOTES, 'UTF-8'); ?>">
                <button class="button" type="submit">Find Registration</button>
            </form>
            <a class="link" href="form.php">Go back to registration form</a>
        <?php endif; ?>
    </div>
</body>
</html>
