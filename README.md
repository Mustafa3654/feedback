# Feedback Portal - PHP/XAMPP Application

A beautiful, interactive feedback form application with a secure PHP backend, MySQL database, and Telegram bot integration. Designed with an elegant, responsive Olive Green theme, support for Light/Dark modes, and a modular architecture.

---

## 🌟 Features

- **Responsive Feedback Form** - Captures Name, Email, Phone, Star Rating (1-5), and Feedback Message.
- **Olive Green Design System** - A sleek, professional Olive Green palette that feels cohesive and premium.
- **Light/Dark Mode** - Smooth transition between light and dark themes, saved persistently via local storage.
- **Modern Animations** - Beautiful micro-interactions, focus rings, hover transitions, and glassmorphic card elements.
- **Structured Database** - Powered by a robust, secure MySQL schema with a database helper class.
- **Telegram Notification Integration** - Receive instant, real-time Telegram alerts whenever a new feedback is submitted.
- **Advanced Admin Dashboard** - Manage Telegram bot configurations, toggle notifications, and secure your credentials.
- **Highly Modular Assets** - Clean separation of concerns with split CSS stylesheets and modular JavaScript files.

---

## 🛠️ Requirements

- **Local Server**: XAMPP, WampServer, MAMP, or any Apache/PHP stack
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher (MariaDB 10.3+)

---

## 🚀 Installation & Setup

### Step 1: Copy Files to Server

1. Copy the entire project folder to your local server's document root:
   - **XAMPP**: `C:\xampp\htdocs\feedback\`
   - **WampServer**: `C:\wamp64\www\feedback\`
   - **Linux/Mac**: `/var/www/html/feedback/`
2. Ensure the folder is named `feedback` so that absolute paths solve correctly.

### Step 2: Database Setup

1. Start Apache and MySQL via the XAMPP Control Panel.
2. Open phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Import the SQL schema file:
   - Click the **Import** tab.
   - Click **Choose File** and select `database/feedback_portal.sql` from your project folder.
   - Click **Go** (or **Import**) to run the script.
   
> [!NOTE]
> The database import will automatically create a database named `feedback_portal` (if it doesn't exist), setup the required tables (`feedbacks`, `telegram_settings`, and `users`), and insert default settings as well as a pre-configured admin account.

### Step 3: Configure Database Connection

If your database username or password differs from the default XAMPP configuration, edit `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedback_portal');
define('DB_USER', 'root');
define('DB_PASS', ''); // Enter your MySQL password here
```

### Step 4: Access the Application

- **Public Feedback Portal**: [http://localhost/feedback/](http://localhost/feedback/)
- **Admin Control Panel**: [http://localhost/feedback/admin/](http://localhost/feedback/admin/)

---

## 🔐 Admin Authentication

To access the Telegram settings dashboard, use the pre-configured admin account:

- **Username**: `admin`
- **Password**: `admin123`

> [!WARNING]
> For security reasons, please change this default password before deploying to any public production environment.

### Changing the Admin Password

The portal uses secure PHP `password_hash()` encryption (bcrypt) to store passwords in the `users` table. To update the password:

1. Generate a new password hash using PHP:
   ```php
   echo password_hash('your_new_password', PASSWORD_DEFAULT);
   ```
2. Update the `password` field for the `admin` user in the `users` table in your database with the newly generated hash.

---

## 🤖 Telegram Bot Integration Setup

1. Open Telegram and search for **@BotFather**.
2. Send the command `/newbot` and follow the instructions to choose a name and username.
3. Copy the **HTTP API Bot Token** provided.
4. Obtain your **Telegram Chat ID**:
   - Start a conversation with your newly created bot.
   - Send any message to the bot.
   - Visit the following URL in your browser (replace `<YOUR_BOT_TOKEN>` with your actual bot token):
     `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
   - In the JSON response, locate the `chat` object under the `message` section and copy the `id` value (e.g., `123456789`).
5. Log into the **Admin Panel**, enter your **Bot Token** and **Chat ID**, toggle **Enable Notifications** on, and click **Save Settings**.

---

## 📂 Project Structure

```text
feedback/
├── index.php                 # Main public feedback submission form
├── README.md                 # Project documentation
│
├── admin/
│   ├── index.php             # Admin settings dashboard
│   └── login.php             # Admin panel secure login page
│
├── api/
│   ├── submit_feedback.php   # Handles AJAX form submission & Telegram dispatch
│   ├── get_telegram_settings.php # JSON endpoint for current settings
│   └── update_telegram_settings.php # JSON endpoint to save updated settings
│
├── css/
│   ├── style.css             # Main stylesheet (Imports all component styles)
│   ├── variables.css         # Theme colors and light/dark mode variables
│   ├── base.css              # Global CSS resets, typography, and scrollbars
│   ├── header.css            # Header bar and main navigation branding
│   ├── card.css              # Glassmorphic card layouts and containers
│   ├── form.css              # Interactive inputs, labels, and validation
│   ├── toast.css             # Floating pop-up notification styles
│   ├── admin.css             # Admin dashboard specific layout styles
│   └── footer.css            # Footer elements and copyright notice
│
├── database/
│   └── feedback_portal.sql   # Complete database schema & default user insert
│
├── includes/
│   ├── config.php            # Global constants, database settings, and CORS headers
│   ├── Database.php          # Singleton PDO database connection wrapper class
│   ├── head.php              # Header meta tags, Google Fonts, and styles
│   ├── header.php            # Site layout top navigation header component
│   └── footer.php            # Site layout footer and scripts loader component
│
└── js/
    ├── app.js                # Main JS entry point (Initializes all event modules)
    ├── theme.js              # Active theme controller (Light/Dark toggler)
    ├── rating.js             # Interactive star-rating selector component
    ├── form.js               # Handles client-side validation and AJAX submit
    ├── toast.js              # Controls success/error notification pop-ups
    └── admin-theme.js        # Dedicated theme management inside the admin panel
```

---

## 🎨 Theme Customization

The interface uses a premium design system centered around custom CSS variables. You can easily adjust colors, borders, and shadows by editing [css/variables.css](file:///d:/xampp/htdocs/feedback/css/variables.css).

### Light Mode Branding
```css
:root {
    --primary: #42522B;        /* Elegant Olive Green */
    --primary-hover: #344022;  /* Slightly darker shade for hovers */
    --background: #ffffff;     /* Base background */
    --card-background: #ffffff;
    --radius: 0.5rem;          /* Rounded corners */
}
```

### Dark Mode Branding
```css
.dark {
    --primary: #5a703b;        /* Softer Olive Green for contrast */
    --primary-hover: #6a8246;
    --background: #0a0a0a;     /* Pure dark background */
    --card-background: #171717;
}
```

---

## 🛡️ Security Best Practices

1. **SQL Injection Protection**: Built fully using prepared queries via PHP PDO in [includes/Database.php](file:///d:/xampp/htdocs/feedback/includes/Database.php).
2. **XSS Protection**: Inputs are thoroughly disinfected using `htmlspecialchars()` before being echoed in HTML.
3. **Password Security**: Supports both secure hashed passwords (using standard PHP bcrypt) and fallback plaintext matches for transition stages.
4. **CORS Restrictions**: Standard RESTful headers are declared, and preflight `OPTIONS` requests are handled safely inside [includes/config.php](file:///d:/xampp/htdocs/feedback/includes/config.php).

---

## 🔍 Troubleshooting

### ❌ Database Connection Error
- Ensure MySQL is running on your server.
- Double-check the host, port, user, and password defined in `includes/config.php`.
- Ensure the database `feedback_portal` exists and the tables were fully imported.

### ❌ Telegram Notifications Not Sending
- Ensure that the "Enable Notifications" toggle is active in your Admin Dashboard.
- Verify your Bot Token and Chat ID are accurate.
- Test your bot directly inside Telegram to verify it is responsive.
- Review your server's outgoing internet connection configuration.

### ❌ Styling or JS Not Rendering
- Clear your browser cache (`Ctrl + F5` or `Cmd + Shift + R`).
- Check your browser Console (`F12`) for any syntax errors or resource loading failures.

---

## 📝 License

This project is open-source and free to customize, modify, and integrate into any personal or professional workflow. Enjoy building!
