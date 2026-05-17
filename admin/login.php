<?php
session_start();
require_once '../includes/Database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username !== '' && $password !== '') {
        try {
            $db = Database::getInstance();
            // Fetch the user from the database
            $user = $db->fetchOne('SELECT * FROM users WHERE username = ?', [$username]);

            if ($user) {
                // Check password: support both modern hashed passwords and plaintext fallback
                $passwordMatch = false;
                if (password_verify($password, $user['password'])) {
                    $passwordMatch = true;
                } else if ($password === $user['password']) {
                    $passwordMatch = true;
                }

                if ($passwordMatch) {
                    $_SESSION['admin_logged_in'] = true;
                    // Sanitize before storing in session to prevent XSS if displayed elsewhere
                    $_SESSION['admin_username'] = htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
                    $_SESSION['admin_role'] = $user['role'];
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Invalid username or password';
                }
            } else {
                $error = 'Invalid username or password';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } else {
        $error = 'Please enter both username and password';
    }
}

// If already logged in, redirect to admin panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Admin Login - Feedback Portal';
$basePath = '../';
$footerText = 'Feedback Portal';
$includeThemeScript = true;
require_once '../includes/head.php';
?>

<div class="login-container">
    <div class="card" style="max-width: 400px;">
        <div class="card-header">
            <a href="../index.php" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Back to Home
            </a>
            <h1 class="card-title">Admin Login</h1>
            <p class="card-description">Enter your credentials to access the admin panel.</p>
        </div>
        <div class="card-content">
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                
                <button type="submit" class="submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Login
                </button>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
