<?php
session_start();
header('Content-Type: application/json');

// ── Check session ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_SESSION['admin_logged_in'])) {
        echo json_encode(['authenticated' => true, 'user' => $_SESSION['admin_user']]);
    } else {
        echo json_encode(['authenticated' => false]);
    }
    exit;
}

// ── Logout (POST) ──────────────────────────────────────────────
session_unset();
session_destroy();
echo json_encode(['success' => true, 'message' => 'Logged out.']);
?>
