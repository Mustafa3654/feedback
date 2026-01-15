<?php
header('Content-Type: application/json');
require_once '../includes/Database.php';

try {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate required fields
    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Name is required']);
        exit;
    }

    if (empty($phone)) {
        echo json_encode(['success' => false, 'error' => 'Phone number is required']);
        exit;
    }

    // Validate email format if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        exit;
    }

    // Validate rating range
    if ($rating < 0 || $rating > 5) {
        $rating = 0;
    }

    // Insert feedback into database
    $db = Database::getInstance();
    $sql = "INSERT INTO feedbacks (name, email, phone, rating, message, createdAt) VALUES (?, ?, ?, ?, ?, NOW())";
    $feedbackId = $db->insert($sql, [$name, $email ?: null, $phone, $rating, $message ?: null]);

    // Send Telegram notification if enabled
    sendTelegramNotification($db, $name, $email, $phone, $rating, $message);

    echo json_encode([
        'success' => true,
        'message' => 'Feedback submitted successfully',
        'id' => $feedbackId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}

function sendTelegramNotification($db, $name, $email, $phone, $rating, $message) {
    try {
        // Get Telegram settings
        $settings = $db->fetchOne("SELECT * FROM telegram_settings WHERE isEnabled = 1 LIMIT 1");
        
        if (!$settings || empty($settings['botToken']) || empty($settings['chatId'])) {
            return false;
        }

        $botToken = $settings['botToken'];
        $chatId = $settings['chatId'];

         // Build message with emoji layout
        $stars = $rating > 0 ? str_repeat('⭐', $rating) : '';
        $ratingText = $rating > 0 ? "{$stars} Rating: {$rating}/5" : 'No rating';
        $text = "📬 <b>New Feedback Received!</b>\n\n";
        $text .= "👤 <b>Name:</b> " . htmlspecialchars($name) . "\n";
        $text .= "📧 <b>Email:</b> " . ($email ?: 'Not provided') . "\n";
        $text .= "📱 <b>Phone:</b> " . htmlspecialchars($phone) . "\n";
        $text .= $ratingText . "\n\n";
        $text .= "💬 <b>Feedback:</b>\n" . ($message ?: 'No message');


        // Send to Telegram
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 10
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        
        return $result !== false;

    } catch (Exception $e) {
        // Log error but don't fail the feedback submission
        error_log("Telegram notification error: " . $e->getMessage());
        return false;
    }
}
