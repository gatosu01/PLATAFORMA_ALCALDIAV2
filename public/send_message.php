<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Controllers/ChatController.php';
use App\Controllers\ChatController;

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$chat = new ChatController();
if ($action === 'sendMessage') {
    $chat->sendMessage();
} elseif ($action === 'getChat') {
    $chat->getChat();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Acción inválida']);
}
