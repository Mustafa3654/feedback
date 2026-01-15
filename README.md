# Feedback Portal - PHP/XAMPP Application

A beautiful feedback form application with PHP backend, MySQL database, and Telegram bot integration. Designed to run on XAMPP or any PHP/MySQL server.

## Features

- **Responsive Feedback Form** - Name, email, phone, star rating, and message fields
- **Light/Dark Mode** - Toggle between light and dark themes
- **Smooth Animations** - Beautiful CSS animations and transitions
- **MySQL Database** - Stores all feedback submissions
- **Telegram Integration** - Receive instant notifications when feedback is submitted
- **Admin Panel** - Configure Telegram bot settings

## Requirements

- XAMPP (or any Apache + PHP + MySQL stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher

## Installation

### Step 1: Copy Files to XAMPP

1. Copy the entire `feedback_hub_php` folder to your XAMPP's `htdocs` directory
2. Rename the folder to `feedback` (or any name you prefer)

```
C:\xampp\htdocs\feedback\
```

### Step 2: Create Database

1. Start XAMPP (Apache and MySQL)
2. Open phpMyAdmin: http://localhost/phpmyadmin
3. Click "Import" tab
4. Select the file: `database/setup.sql`
5. Click "Go" to run the SQL

**Or manually:**
1. Create a new database called `feedback_portal`
2. Run the SQL from `database/setup.sql`

### Step 3: Configure Database Connection

Edit `includes/config.php` and update the database settings:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedback_portal');
define('DB_USER', 'root');
define('DB_PASS', ''); // Your MySQL password (empty for default XAMPP)
```

### Step 4: Access the Application

- **Feedback Form:** http://localhost/feedback/
- **Admin Panel:** http://localhost/feedback/admin/

## Admin Login

Default credentials (change in production!):
- **Username:** admin
- **Password:** admin123

To change admin credentials, edit `admin/login.php`:
```php
define('ADMIN_USERNAME', 'your_username');
define('ADMIN_PASSWORD', 'your_secure_password');
```

## Telegram Bot Setup

1. Open Telegram and search for **@BotFather**
2. Send `/newbot` and follow the instructions
3. Copy the **Bot Token** provided
4. To get your **Chat ID**:
   - Start a chat with your bot
   - Send any message to the bot
   - Visit: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
   - Find your chat ID in the response
5. Go to Admin Panel → Enter Bot Token and Chat ID → Enable notifications

## File Structure

```
feedback_hub_php/
├── index.php              # Main feedback form page
├── README.md              # This file
├── admin/
│   ├── index.php          # Admin panel for Telegram settings
│   └── login.php          # Admin login page
├── api/
│   ├── submit_feedback.php        # API endpoint for form submission
│   ├── get_telegram_settings.php  # Get Telegram settings (admin)
│   └── update_telegram_settings.php # Update Telegram settings (admin)
├── css/
│   └── style.css          # All styles (light/dark mode)
├── database/
│   └── setup.sql          # Database setup script
├── includes/
│   ├── config.php         # Database configuration
│   └── Database.php       # Database connection class
└── js/
    └── app.js             # Frontend JavaScript
```

## Customization

### Changing Colors

Edit `css/style.css` and modify the CSS variables in `:root` (light mode) and `.dark` (dark mode):

```css
:root {
    --primary: #2563eb;        /* Main button color */
    --background: #ffffff;     /* Page background */
    --foreground: #1a1a1a;     /* Text color */
    /* ... more variables */
}
```

### Changing Admin Password

For better security, consider implementing proper password hashing:

```php
// In admin/login.php, replace plain text password with:
define('ADMIN_PASSWORD_HASH', password_hash('your_password', PASSWORD_DEFAULT));

// Then verify with:
if (password_verify($password, ADMIN_PASSWORD_HASH)) {
    // Login successful
}
```

## Security Notes

1. **Change default admin credentials** before deploying to production
2. **Use HTTPS** in production for secure data transmission
3. **Consider implementing CSRF protection** for forms
4. **Sanitize all user inputs** (already implemented)
5. **Use prepared statements** for database queries (already implemented)

## Troubleshooting

### Database Connection Error
- Check that MySQL is running in XAMPP
- Verify database credentials in `includes/config.php`
- Ensure the `feedback_portal` database exists

### Telegram Notifications Not Working
- Verify bot token is correct
- Ensure chat ID is correct (must be a number)
- Check that "Enable Notifications" is turned on
- Test the bot by sending a message directly

### 404 Error on API Calls
- Ensure Apache mod_rewrite is enabled
- Check that the folder structure is correct

## License

This project is open source and available for personal and commercial use.

## Support

For issues or questions, please check the troubleshooting section above.
