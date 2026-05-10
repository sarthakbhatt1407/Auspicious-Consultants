<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// db_config.php is in the same api/ folder
require_once __DIR__ . '/db_config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed. Use POST.']);
    exit;
}

// ─── Sanitize & Validate Inputs ───────────────────────────────────────────────

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$name    = sanitize($_POST['name']    ?? '');
$email   = sanitize($_POST['email']   ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = 'Full Name is required.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid Email Address is required.';
}

if (empty($subject)) {
    $errors[] = 'Subject is required.';
}

if (empty($message)) {
    $errors[] = 'Message is required.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// ─── Insert Into Database ──────────────────────────────────────────────────────

$conn = getDBConnection();

$stmt = $conn->prepare(
    "INSERT INTO contact_messages (name, email, subject, message, submitted_at)
     VALUES (?, ?, ?, ?, NOW())"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$stmt->bind_param('ssss', $name, $email, $subject, $message);

if ($stmt->execute()) {
    $insertedId = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Thank you, ' . $name . '! Your message has been sent successfully. We will get back to you shortly.',
        'id'      => $insertedId
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save your message. Please try again.']);
}

$stmt->close();
$conn->close();
?>
