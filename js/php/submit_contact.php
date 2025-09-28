<?php
// php/submit_contact.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']); exit;
}

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$message = trim($data['message'] ?? '');

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Name and valid email required']); exit;
}

// connect
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    // do NOT expose details in production
    echo json_encode(['success' => false, 'message' => 'Database connection failed']); exit;
}

$stmt = $mysqli->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Server error']); exit;
}
$stmt->bind_param('ssss', $name, $email, $phone, $message);
$ok = $stmt->execute();
$stmt->close();
$mysqli->close();

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Insert failed']);
}
