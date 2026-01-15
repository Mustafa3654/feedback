// Feedback Portal - JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initThemeToggle();
    initStarRating();
    initFormSubmission();
});

// Theme Toggle
function initThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('theme') || 'dark';
    if (savedTheme === 'light') {
        html.classList.add('light');
        updateThemeIcon(false);
    }
    
    themeToggle.addEventListener('click', function() {
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcon(isDark);
        
        // Add rotation animation
        const icon = themeToggle.querySelector('svg');
        icon.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            icon.style.transform = '';
        }, 300);
    });
}

function updateThemeIcon(isDark) {
    const themeToggle = document.getElementById('theme-toggle');
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
        themeToggle.setAttribute('aria-label', 'Switch to light mode');
    } else {
        themeToggle.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
            </svg>
        `;
        themeToggle.setAttribute('aria-label', 'Switch to dark mode');
    }
}

// Star Rating
function initStarRating() {
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating');
    let currentRating = 0;
    
    starButtons.forEach((btn, index) => {
        const starValue = index + 1;
        
        btn.addEventListener('click', function() {
            currentRating = starValue;
            ratingInput.value = starValue;
            updateStars(starValue);
        });
        
        btn.addEventListener('mouseenter', function() {
            highlightStars(starValue);
        });
        
        btn.addEventListener('mouseleave', function() {
            updateStars(currentRating);
        });
    });
}

function updateStars(rating) {
    const starButtons = document.querySelectorAll('.star-btn');
    starButtons.forEach((btn, index) => {
        if (index < rating) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

function highlightStars(rating) {
    const starButtons = document.querySelectorAll('.star-btn');
    starButtons.forEach((btn, index) => {
        if (index < rating) {
            btn.classList.add('hover');
        } else {
            btn.classList.remove('hover');
        }
    });
}

// Form Submission
function initFormSubmission() {
    const form = document.getElementById('feedback-form');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate required fields
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        if (!name) {
            showToast('error', 'Validation Error', 'Name is required');
            return;
        }
        
        if (!phone) {
            showToast('error', 'Validation Error', 'Phone number is required');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
            </svg>
            Submitting...
        `;
        
        try {
            const formData = new FormData(form);
            const response = await fetch('api/submit_feedback.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('success', 'Thank you for your feedback!', 'Your feedback has been submitted successfully.');
                form.reset();
                updateStars(0);
                document.getElementById('rating').value = '0';
            } else {
                showToast('error', 'Submission Failed', result.error || 'An error occurred while submitting your feedback.');
            }
        } catch (error) {
            showToast('error', 'Connection Error', 'Unable to connect to the server. Please try again.');
            console.error('Error:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
            submitBtn.innerHTML = originalText;
        }
    });
}

// Toast Notifications
function showToast(type, title, message) {
    const container = document.getElementById('toast-container');
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const iconSvg = type === 'success' 
        ? `<svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
           </svg>`
        : `<svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
           </svg>`;
    
    toast.innerHTML = `
        ${iconSvg}
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 5000);
}
