<?php
require __DIR__ . '/../vendor/autoload.php';
require 'generateUID.php';
require 'JWTHandler.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $clientIds;
    protected $database;
    protected $jwt;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->clientIds = [];
        $host = 'localhost';
        $dbname = 'webchat_ws';
        $username = 'root';
        $password = '';
        $this->database = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->jwt = new JWTHandler();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->clientIds[$conn->resourceId] = $conn;
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $messageData = json_decode($msg, true);

        switch ($messageData['type']) {
            case 'chat_message':
                $this->sendChatMessage($from, $messageData);
                break;
            case 'logged_in':
                // TODO: User logged in to the same account on different device have issues in receiving a message due to user_id is used in clientId
                $this->clientIds[$messageData['user_id']] = $from;
                break;
            // Handle other message types as needed
        }
    }

    protected function sendChatMessage(ConnectionInterface $from, $messageData) {
        $webchatToken = $messageData['tk'];
        $jwt_valid = $this->jwt->validateToken($webchatToken);
        if (!$jwt_valid['is_valid']) {
            return;
        }
        $randomChatId = generateUID();
        $senderUserId = $jwt_valid['payload']['user_id'];
        $senderAccountId = $jwt_valid['payload']['acc_id'];
        $receiverUserId = $messageData['receiver_user_id'];
        $messageContent = htmlspecialchars($messageData['content'], ENT_QUOTES, 'UTF-8');
        
        $stmt = $this->database->prepare("INSERT INTO chats (chat_id, text_sent, sender_user_id, receiver_user_id, sent_dt) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$randomChatId, $messageContent, $senderUserId, $receiverUserId]);

        $this->informUser($senderUserId, $messageContent, $senderUserId, $receiverUserId);
        $this->informUser($receiverUserId, $messageContent, $senderUserId, $receiverUserId);
    }

    protected function informUser($userId, $messageContent, $senderUserId, $receiverUserId) {
        if (isset($this->clientIds[$userId])) {
            $this->clientIds[$userId]->send(json_encode([
                'type' => 'chat_message',
                'content' => $messageContent,
                'sender_user_id' => $senderUserId,
                'receiver_user_id' => $receiverUserId,
                'sent_dt' => date('Y-m-d H:i:s')
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        $key = array_search($conn, $this->clientIds);
        if ($key !== false) {
            unset($this->clientIds[$key]);
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080,
    '0.0.0.0'
);

echo "WebSocket server running...\n";

$server->run();