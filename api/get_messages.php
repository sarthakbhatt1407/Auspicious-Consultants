<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
    exit;
}

$conn = getDBConnection();

// Summary stats
$statsQuery = $conn->query("
    SELECT
        COUNT(*) AS total,
        SUM(is_read = 0) AS unread,
        SUM(DATE(submitted_at) = CURDATE()) AS today
    FROM contact_messages
");
$stats = $statsQuery->fetch_assoc();

// All messages, newest first
$result = $conn->query("
    SELECT id, name, email, subject, message, submitted_at, is_read
    FROM contact_messages
    ORDER BY submitted_at DESC
");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode([
    'success'  => true,
    'stats'    => $stats,
    'messages' => $messages
]);

$conn->close();
?>
