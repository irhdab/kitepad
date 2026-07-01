<?php
require_once 'db.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $uid = $_GET['uid'] ?? '';
        if (!$uid) {
            http_response_code(400);
            echo json_encode(['message' => 'uid is required']);
            exit;
        }
        echo json_encode(['uid' => $uid, 'counts' => get_reaction_counts_for_uid($pdo, $uid)]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    if (!check_rate_limit($pdo, 'reactions', 40, 60)) {
        http_response_code(429);
        echo json_encode(['message' => 'Too many reaction requests']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $uid = $data['uid'] ?? '';
    $emoji = $data['emoji'] ?? '';
    $valid = ["👍", "🔥", "💯"];

    if (!$uid || !in_array($emoji, $valid, true)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid uid or emoji']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT uid FROM writings WHERE uid = ? AND (expires_at IS NULL OR expires_at > NOW())");
    $stmt->execute([$uid]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['message' => 'Post not found']);
        exit;
    }

    $ip = get_client_ip();
    $ipHash = hash('sha256', $ip . '|kitepad_reactions');

    $delete = $pdo->prepare("DELETE FROM reactions WHERE writing_uid = ? AND emoji = ? AND ip_hash = ?");
    $delete->execute([$uid, $emoji, $ipHash]);
    $toggledOff = $delete->rowCount() > 0;

    if (!$toggledOff) {
        $insert = $pdo->prepare("INSERT INTO reactions (writing_uid, emoji, ip_hash) VALUES (?, ?, ?)");
        $insert->execute([$uid, $emoji, $ipHash]);
    }

    echo json_encode([
        'message' => 'OK',
        'active' => !$toggledOff,
        'counts' => get_reaction_counts_for_uid($pdo, $uid)
    ]);
} catch (Throwable $e) {
    error_log('Reaction error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Server error']);
}
?>
