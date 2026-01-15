<!-- Footer -->
<footer>
    &copy; <?php echo date('Y'); ?> <?php echo isset($footerText) ? htmlspecialchars($footerText) : 'AlphaSoft'; ?>. All rights reserved.
</footer>

<!-- Toast Container -->
<div id="toast-container" class="toast-container"></div>

<?php if (isset($includeThemeScript) && $includeThemeScript): ?>
<script>
    // Theme support
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
</script>
<?php endif; ?>

<?php if (isset($jsFiles)): ?>
    <?php foreach ($jsFiles as $jsFile): ?>
        <script src="<?php echo isset($basePath) ? $basePath : ''; ?><?php echo htmlspecialchars($jsFile); ?>"></script>
    <?php endforeach; ?>
<?php else: ?>
    <!-- Load JS modules in order -->
    <script src="<?php echo isset($basePath) ? $basePath : ''; ?>js/toast.js"></script>
    <script src="<?php echo isset($basePath) ? $basePath : ''; ?>js/theme.js"></script>
    <script src="<?php echo isset($basePath) ? $basePath : ''; ?>js/rating.js"></script>
    <script src="<?php echo isset($basePath) ? $basePath : ''; ?>js/form.js"></script>
    <script src="<?php echo isset($basePath) ? $basePath : ''; ?>js/app.js"></script>
<?php endif; ?>

</body>
</html>
