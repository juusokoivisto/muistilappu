<?php
require_once "connect.php";

session_start();

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['id'];

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

try {
    $conn->beginTransaction();

    $deleteSql = "DELETE FROM notes WHERE user_id = :user_id";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->execute(['user_id' => $user_id]);

    $insertSql = "INSERT INTO notes (user_id, z_index, data, pos_left, pos_top)
                  VALUES (:user_id, :z_index, :data, :pos_left, :pos_top)";
    $insertStmt = $conn->prepare($insertSql);

    foreach ($data as $note) {
        $z_index = intval($note['z_index']);
        $left = max(0, min(intval($note['left']), 10000));
        $top = max(0, min(intval($note['top']), 10000));
        $noteData = substr($note['data'] ?? '', 0, 255);

        $insertStmt->execute([
            'user_id' => $user_id,
            'z_index' => $z_index,
            'data' => $noteData,
            'pos_left' => $left,
            'pos_top' => $top
        ]);
    }

    $conn->commit();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollBack();

    http_response_code(500);
    echo json_encode(['error' => 'Failed to save notes']);
}

function clamp($value, $min, $max) {
    return max($min, min($value, $max));
}
?> 
