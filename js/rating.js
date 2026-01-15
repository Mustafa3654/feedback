// Star Rating
function initStarRating() {
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating');
    if (!ratingInput) return;
    
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
