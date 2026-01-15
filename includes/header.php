<!-- Header -->
<header>
    
    <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        
        <span>Feedback Portal</span>
        <?php if (isset($showAdminLink) && $showAdminLink): ?>
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>admin/login.php" style="text-decoration: none; color: var(--primary-foreground);"></a>
        <?php endif; ?>
        <a href="admin/login.php" style="text-decoration: none; color: transparent;">Admin</a>
    </div>
    
    <?php if (isset($showNavLinks) && $showNavLinks): ?>
        <div class="nav-links">
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>index.php" class="nav-link">
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
    <?php else: ?>
        <button id="theme-toggle" class="theme-toggle" aria-label="Switch to dark mode">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
            </svg>
        </button>
    <?php endif; ?>
</header>
