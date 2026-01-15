<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/Database.php';

$db = Database::getInstance();
$settings = $db->fetchOne("SELECT * FROM telegram_settings LIMIT 1");

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $botToken = isset($_POST['botToken']) ? trim($_POST['botToken']) : '';
    $chatId = isset($_POST['chatId']) ? trim($_POST['chatId']) : '';
    $isEnabled = isset($_POST['isEnabled']) ? 1 : 0;

    try {
        if ($settings) {
            $sql = "UPDATE telegram_settings SET botToken = ?, chatId = ?, isEnabled = ?, updatedAt = NOW() WHERE id = ?";
            $db->update($sql, [$botToken, $chatId, $isEnabled, $settings['id']]);
        } else {
            $sql = "INSERT INTO telegram_settings (botToken, chatId, isEnabled, createdAt, updatedAt) VALUES (?, ?, ?, NOW(), NOW())";
            $db->insert($sql, [$botToken, $chatId, $isEnabled]);
        }
        
        // Refresh settings
        $settings = $db->fetchOne("SELECT * FROM telegram_settings LIMIT 1");
        $success = 'Settings saved successfully!';
    } catch (Exception $e) {
        $error = 'Error saving settings: ' . $e->getMessage();
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$pageTitle = 'Admin Panel - Feedback Portal';
$basePath = '../';
$showNavLinks = true;
$footerText = 'Feedback Portal';
$jsFiles = ['js/admin-theme.js'];
require_once '../includes/head.php';
require_once '../includes/header.php';
?>

<!-- Main Content -->
<main>
    <div class="card" style="max-width: 450px;">
        <div class="card-header">
            <div class="admin-header">
                <div></div>
                <a href="?logout=1" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>
            <div class="bot-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                    <circle cx="12" cy="5" r="2"></circle>
                    <path d="M12 7v4"></path>
                    <line x1="8" y1="16" x2="8" y2="16"></line>
                    <line x1="16" y1="16" x2="16" y2="16"></line>
                </svg>
            </div>
            <h1 class="card-title">Telegram Bot Settings</h1>
            <p class="card-description">Configure your Telegram bot to receive feedback notifications.</p>
        </div>
        <div class="card-content">
            <?php if ($success): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="botToken">Bot Token</label>
                    <input type="text" id="botToken" name="botToken" 
                           placeholder="Enter your Telegram bot token"
                           value="<?php echo htmlspecialchars($settings['botToken'] ?? ''); ?>">
                    <p class="help-text">Get your bot token from @BotFather on Telegram</p>
                </div>
                
                <div class="form-group">
                    <label for="chatId">Chat ID</label>
                    <input type="text" id="chatId" name="chatId" 
                           placeholder="Enter your Telegram chat ID"
                           value="<?php echo htmlspecialchars($settings['chatId'] ?? ''); ?>">
                    <p class="help-text">The chat ID where notifications will be sent</p>
                </div>
                
                <div class="form-group">
                    <div class="toggle-container">
                        <div class="toggle-label">
                            <span>Enable Notifications</span>
                            <small>Send feedback notifications to Telegram</small>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="isEnabled" <?php echo ($settings['isEnabled'] ?? 0) ? 'checked' : ''; ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Save Settings
                </button>
            </form>
        </div>
    </div>
</main>

<?php
require_once '../includes/footer.php';
?>
