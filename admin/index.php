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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Feedback Portal</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--foreground);
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .logout-btn:hover {
            background-color: var(--muted);
            border-color: var(--foreground);
        }
        .success-message {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid #22c55e;
            color: #22c55e;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #ef4444;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
        }
        .toggle-label {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .toggle-label span {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--card-foreground);
        }
        .toggle-label small {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--muted);
            transition: 0.3s;
            border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        .toggle-switch input:checked + .toggle-slider {
            background-color: var(--primary);
        }
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }
        .bot-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
        .bot-icon svg {
            width: 48px;
            height: 48px;
            color: var(--primary);
        }
        .help-text {
            font-size: 0.75rem;
            color: var(--muted-foreground);
            margin-top: 0.25rem;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--muted-foreground);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }
        .nav-link:hover {
            color: var(--foreground);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Feedback Portal</span>
        </div>
        <div class="nav-links">
            <a href="../index.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Back
            </a>
            <button id="theme-toggle" class="theme-toggle" aria-label="Switch to dark mode">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                </svg>
            </button>
        </div>
    </header>

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
                        <input type="password" id="botToken" name="botToken" 
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

    <!-- Footer -->
    <footer>
        &copy; 2026 Feedback Portal. All rights reserved.
    </footer>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            html.classList.add('dark');
            updateThemeIcon(true);
        }
        
        themeToggle.addEventListener('click', function() {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        });

        function updateThemeIcon(isDark) {
            if (isDark) {
                themeToggle.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2"></path>
                        <path d="M12 20v2"></path>
                        <path d="m4.93 4.93 1.41 1.41"></path>
                        <path d="m17.66 17.66 1.41 1.41"></path>
                        <path d="M2 12h2"></path>
                        <path d="M20 12h2"></path>
                        <path d="m6.34 17.66-1.41 1.41"></path>
                        <path d="m19.07 4.93-1.41 1.41"></path>
                    </svg>
                `;
            } else {
                themeToggle.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                    </svg>
                `;
            }
        }
    </script>
</body>
</html>
