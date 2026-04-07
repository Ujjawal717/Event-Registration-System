<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $event = trim($_POST['event'] ?? '');

    if ($name === '' || $email === '' || $event === '') {
        header('Location: form.php?error=' . urlencode('Name, email, and event are required.'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: form.php?error=' . urlencode('Please provide a valid email address.'));
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration_form_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        header('Location: form.php?error=' . urlencode('Connection failed: ' . $conn->connect_error));
        exit;
    }

    $sql = "CREATE TABLE IF NOT EXISTS registrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        event VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) !== TRUE) {
        header('Location: form.php?error=' . urlencode('Table creation failed: ' . $conn->error));
        exit;
    }

    $alterSql = "ALTER TABLE registrations ADD COLUMN IF NOT EXISTS email VARCHAR(100) NOT NULL AFTER name";
    if ($conn->query($alterSql) !== TRUE && $conn->errno !== 1060) {
        header('Location: form.php?error=' . urlencode('Schema update failed: ' . $conn->error));
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO registrations (name, email, event) VALUES (?, ?, ?)");
    if (!$stmt) {
        header('Location: form.php?error=' . urlencode('Prepare failed: ' . $conn->error));
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $event);
    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        header('Location: view.php?id=' . intval($insertId));
    } else {
        header('Location: form.php?error=' . urlencode('Insert failed: ' . $stmt->error));
    }

    $stmt->close();
    $conn->close();
    exit;
}

header('Location: form.php');
exit;
?>