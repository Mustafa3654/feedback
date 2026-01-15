<?php
$pageTitle = 'Feedback Portal';
$basePath = '';
$showAdminLink = true;
$footerText = 'AlphaSoft';
require_once 'includes/head.php';
require_once 'includes/header.php';
?>

<!-- Main Content -->
<main>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Share Your Feedback</h1>
            <p class="card-description">We value your opinion! Please take a moment to share your experience with us.</p>
        </div>
        <div class="card-content">
            <form id="feedback-form">
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email (Optional)</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email">
                </div>

                <!-- Phone Field -->
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>

                <!-- Rating -->
                <div class="form-group">
                    <label>Rating</label>
                    <input type="hidden" id="rating" name="rating" value="0">
                    <div class="rating-container">
                        <button type="button" class="star-btn" data-value="1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                        <button type="button" class="star-btn" data-value="2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                        <button type="button" class="star-btn" data-value="3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                        <button type="button" class="star-btn" data-value="4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                        <button type="button" class="star-btn" data-value="5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label for="message">Your Feedback <span class="required">*</span></label>
                    <textarea id="message" name="message" placeholder="Tell us about your experience..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Submit Feedback
                </button>
            </form>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
?>
