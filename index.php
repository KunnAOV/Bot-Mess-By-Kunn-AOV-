<?php
include 'config.php';
header("Content-Type: application/json");

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['hub_verify_token'])) {
    echo ($input['hub_verify_token'] === VERIFY_TOKEN) ? $input['hub_challenge'] : 'Invalid token';
    exit;
}

if (isset($input['entry'][0]['messaging'])) {
    foreach ($input['entry'][0]['messaging'] as $event) {
        if (!empty($event['message']) && isset($event['sender']['id'])) {
            $senderId = $event['sender']['id'];
            $messageText = strtolower(trim($event['message']['text']));

            switch ($messageText) {
                case "/start":
                    sendMessage($senderId, "🎉 Bot Mess By Kunn AOV 🎉\nAdmin: $admin\n$contact\nNhập /menu để xem chức năng.");
                    break;
                case "/menu":
                    sendMessage($senderId, "📜 Menu Bot 📜\n👑 Admin: $admin\n💰 /money check - Xem số tiền\n🎁 /money daily - Nhận tiền miễn phí\n🔄 /money pay <số> <@user> - Chuyển tiền\n🎲 /taixiu <tài/xỉu> <số> - Chơi tài xỉu\n🎰 /jackpot <số> - Chơi JackPot\n🎨 /stk <relay tin nhắn> - Đổi ảnh thành sticker\n📽️ /gif <relay tin nhắn> - Đổi video thành GIF\n⚡ Bot luôn sẵn sàng!");
                    break;
                default:
                    sendMessage($senderId, "⚠️ Lệnh không hợp lệ! Nhập /menu để xem danh sách lệnh.");
                    break;
            }
        }
    }
}

function sendMessage($recipientId, $messageText) {
    $data = [
        "recipient" => ["id" => $recipientId],
        "message" => ["text" => $messageText]
    ];
    callSendAPI($data);
}

function callSendAPI($data) {
    $ch = curl_init("https://graph.facebook.com/v17.0/me/messages?access_token=" . ACCESS_TOKEN);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
