<?php
require_once "connect.php";

session_start();

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['id'];

$sql = "SELECT * FROM notes WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($notes);
?>
