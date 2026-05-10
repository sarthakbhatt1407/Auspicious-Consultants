<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// ─── Admin Credentials ─────────────────────────────────────────
// Change these before going live!
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Auspicious@2024');   // plain text — change to your own
// ──────────────────────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
    exit;
}

$input    = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($username) || empty($password)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}

// Brute-force delay
sleep(1);

if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user']      = $username;
    $_SESSION['login_time']      = time();
    echo json_encode(['success' => true, 'message' => 'Login successful.']);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
}
?>
