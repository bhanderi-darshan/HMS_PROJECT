document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle Functionality
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', function() {
            // Toggle active class on clicked item
            item.classList.toggle('active');
            
            // Update icon
            const icon = item.querySelector('.toggle-icon i');
            if (item.classList.contains('active')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-plus';
            }
            
            // Close other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.toggle-icon i').className = 'fas fa-plus';
                }
            });
        });
    });
    
    // Form Validation
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Get form fields
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            // Reset error states
            clearErrors();
            
            // Validate name
            if (!name.value.trim()) {
                showError(name, 'Please enter your name');
                isValid = false;
            }
            
            // Validate email
            if (!email.value.trim()) {
                showError(email, 'Please enter your email address');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            }
            
            // Validate subject
            if (!subject.value.trim()) {
                showError(subject, 'Please enter a subject');
                isValid = false;
            }
            
            // Validate message
            if (!message.value.trim()) {
                showError(message, 'Please enter your message');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
    
    // Email validation helper
    function isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
    
    // Show error message
    function showError(input, message) {
        const formGroup = input.parentElement;
        const errorMessage = document.createElement('div');
        errorMessage.className = 'input-error-message';
        errorMessage.textContent = message;
        errorMessage.style.color = 'var(--error-color)';
        errorMessage.style.fontSize = '0.8rem';
        errorMessage.style.marginTop = '5px';
        
        input.style.borderColor = 'var(--error-color)';
        formGroup.appendChild(errorMessage);
        
        // Add event listener to clear error on input
        input.addEventListener('input', function() {
            clearError(input);
        });
    }
    
    // Clear a specific error
    function clearError(input) {
        const formGroup = input.parentElement;
        const errorMessage = formGroup.querySelector('.input-error-message');
        
        if (errorMessage) {
            formGroup.removeChild(errorMessage);
        }
        
        input.style.borderColor = '';
    }
    
    // Clear all errors
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.input-error-message');
        const inputs = document.querySelectorAll('input, textarea');
        
        errorMessages.forEach(message => {
            message.parentElement.removeChild(message);
        });
        
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }
    
    // Google Maps API Integration (Demo)
    // This is just a placeholder - in a real application, you would include proper Google Maps integration
    const mapContainer = document.querySelector('.map-container');
    
    if (mapContainer) {
        // If needed, additional map functionality would go here
        console.log('Map container found. Ready for Google Maps integration.');
    }
    
    // Auto-focus first form field
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.focus();
    }
});