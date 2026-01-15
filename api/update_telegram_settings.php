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

    // Get POST data
    $botToken = isset($_POST['botToken']) ? trim($_POST['botToken']) : '';
    $chatId = isset($_POST['chatId']) ? trim($_POST['chatId']) : '';
    $isEnabled = isset($_POST['isEnabled']) ? intval($_POST['isEnabled']) : 0;

    $db = Database::getInstance();
    
    // Check if settings exist
    $existing = $db->fetchOne("SELECT id FROM telegram_settings LIMIT 1");

    if ($existing) {
        // Update existing settings
        $sql = "UPDATE telegram_settings SET botToken = ?, chatId = ?, isEnabled = ?, updatedAt = NOW() WHERE id = ?";
        $db->update($sql, [$botToken, $chatId, $isEnabled, $existing['id']]);
    } else {
        // Insert new settings
        $sql = "INSERT INTO telegram_settings (botToken, chatId, isEnabled, createdAt, updatedAt) VALUES (?, ?, ?, NOW(), NOW())";
        $db->insert($sql, [$botToken, $chatId, $isEnabled]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Settings updated successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
