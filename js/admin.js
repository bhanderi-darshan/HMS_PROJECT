document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    const adminContent = document.querySelector('.admin-content');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
            adminContent.classList.toggle('sidebar-active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isSidebarClick = adminSidebar?.contains(event.target);
        const isToggleClick = sidebarToggle?.contains(event.target);
        
        if (window.innerWidth <= 768 && !isSidebarClick && !isToggleClick && adminSidebar?.classList.contains('active')) {
            adminSidebar.classList.remove('active');
            adminContent.classList.remove('sidebar-active');
        }
    });
    
    // Notifications
    const notificationItems = document.querySelectorAll('.notification-item');
    const markAllRead = document.querySelector('.mark-all-read');
    
    if (markAllRead) {
        markAllRead.addEventListener('click', function(e) {
            e.preventDefault();
            
            notificationItems.forEach(item => {
                item.classList.remove('unread');
            });
            
            const notificationCount = document.querySelector('.notification-count');
            if (notificationCount) {
                notificationCount.textContent = '0';
            }
        });
    }
    
    // Table row actions
    const actionButtons = document.querySelectorAll('.action-buttons .btn-icon');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.getAttribute('data-action');
            const itemId = this.closest('tr').getAttribute('data-id');
            
            if (action === 'delete') {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this item?')) {
                    // In a real application, this would submit a form or make an AJAX request
                    console.log(`Delete item ${itemId}`);
                }
            }
        });
    });
    
    // Form validation for admin forms
    const adminForms = document.querySelectorAll('form.admin-form');
    
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    
                    // Add error class
                    field.classList.add('error');
                    
                    // Create error message if it doesn't exist
                    let errorMessage = field.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('error-message');
                        errorMessage.textContent = 'This field is required';
                        field.parentNode.insertBefore(errorMessage, field.nextSibling);
                    }
                    
                    // Remove error on input
                    field.addEventListener('input', function() {
                        if (field.value.trim()) {
                            field.classList.remove('error');
                            if (errorMessage) {
                                errorMessage.remove();
                            }
                        }
                    });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    });
    
    // Image preview for file inputs
    const imageInputs = document.querySelectorAll('.image-upload input[type="file"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.parentElement.querySelector('.image-preview');
            
            if (preview) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.style.backgroundImage = `url(${e.target.result})`;
                        preview.classList.add('has-image');
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });
    
    // Date range picker initialization (if needed)
    const dateRangeInputs = document.querySelectorAll('.date-range');
    
    if (dateRangeInputs.length > 0) {
        // This would typically use a library like daterangepicker
        console.log('Date range inputs found. Initialize date range picker here.');
    }
    
    // Tabs functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to current button and content
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
});