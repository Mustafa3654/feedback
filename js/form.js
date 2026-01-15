// Form Submission
function initFormSubmission() {
    const form = document.getElementById('feedback-form');
    if (!form) return;
    
    const submitBtn = document.getElementById('submit-btn');
    if (!submitBtn) return;
    
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
                if (typeof updateStars === 'function') {
                    updateStars(0);
                }
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
