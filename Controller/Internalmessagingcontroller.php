<?php
// FR-46: Internal Messaging
// PHP message send/receive handler; messages stored in MySQL messages table;
// rendered via JavaScript fetch on recipient page.

session_start();

// Must be logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized. Please log in."]);
    exit();
}

header('Content-Type: application/json');

$sender_id = $_SESSION['user_id'];

// ─── SEND MESSAGE ──────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    // Validate recipient_id
    $recipient_id = trim($input["recipient_id"] ?? "");
    if (empty($recipient_id) || !ctype_digit($recipient_id)) {
        echo json_encode(["error" => "Invalid or missing recipient ID."]);
        exit();
    }

    // Prevent messaging yourself
    if ((int)$recipient_id === (int)$sender_id) {
        echo json_encode(["error" => "You cannot send a message to yourself."]);
        exit();
    }

    // Validate message content
    $message_content = trim($input["message_content"] ?? "");
    if (empty($message_content)) {
        echo json_encode(["error" => "Message content cannot be empty."]);
        exit();
    }
    if (strlen($message_content) > 2000) {
        echo json_encode(["error" => "Message must not exceed 2000 characters."]);
        exit();
    }

    $safe_content = htmlspecialchars($message_content);
    $timestamp    = date("Y-m-d H:i:s");

    include_once '../Model/MessageModel.php';
    $messageModel = new MessageModel();

    $inserted = $messageModel->insertMessage($sender_id, (int)$recipient_id, $safe_content, $timestamp);

    if ($inserted) {
        echo json_encode(["success" => true, "message" => "Message sent.", "timestamp" => $timestamp]);
    } else {
        echo json_encode(["error" => "Failed to send message. Please try again."]);
    }
    exit();
}

// ─── FETCH MESSAGES (GET) ──────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $recipient_id = trim($_GET["recipient_id"] ?? "");

    if (empty($recipient_id) || !ctype_digit($recipient_id)) {
        echo json_encode(["error" => "Invalid or missing recipient ID."]);
        exit();
    }

    include_once '../Model/MessageModel.php';
    $messageModel = new MessageModel();

    // Fetch conversation between sender and recipient
    $messages = $messageModel->getConversation($sender_id, (int)$recipient_id);

    if ($messages === false) {
        echo json_encode(["error" => "Failed to load messages. Please try again."]);
    } else {
        echo json_encode(["success" => true, "messages" => $messages]);
    }
    exit();
}

// Any other HTTP method
http_response_code(405);
echo json_encode(["error" => "Method not allowed."]);
?>