<?php
header('Content-Type: application/json');
require_once '../includes/Database.php';

// Simple session-based authentication check
session_start();

try {
    // Check if user is logged in as admin
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        exit;
    }

    $db = Database::getInstance();
    $settings = $db->fetchOne("SELECT id, botToken, chatId, isEnabled, createdAt, updatedAt FROM telegram_settings LIMIT 1");

    if ($settings) {
        // Mask the bot token for security (show only last 4 characters)
        if (!empty($settings['botToken'])) {
            $settings['botTokenMasked'] = '••••••••' . substr($settings['botToken'], -4);
        } else {
            $settings['botTokenMasked'] = '';
        }
        
        echo json_encode([
            'success' => true,
            'settings' => $settings
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'settings' => null
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
