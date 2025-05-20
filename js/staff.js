document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const staffSidebar = document.querySelector('.staff-sidebar');
    const staffContent = document.querySelector('.staff-content');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            staffSidebar.classList.toggle('active');
            staffContent.classList.toggle('sidebar-active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isSidebarClick = staffSidebar?.contains(event.target);
        const isToggleClick = sidebarToggle?.contains(event.target);
        
        if (window.innerWidth <= 768 && !isSidebarClick && !isToggleClick && staffSidebar?.classList.contains('active')) {
            staffSidebar.classList.remove('active');
            staffContent.classList.remove('sidebar-active');
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
    
    // Status update confirmation
    const statusButtons = document.querySelectorAll('.btn-icon.confirm, .btn-icon.complete, .btn-icon.cancel');
    
    statusButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.classList.contains('confirm') ? 'confirm' : 
                          this.classList.contains('complete') ? 'complete' : 'cancel';
            
            const message = action === 'confirm' ? 'Are you sure you want to confirm this appointment?' :
                           action === 'complete' ? 'Are you sure you want to mark this appointment as completed?' :
                           'Are you sure you want to cancel this appointment?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Appointment search functionality
    const searchInput = document.getElementById('appointment-search');
    const appointmentRows = document.querySelectorAll('.staff-table tbody tr');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            appointmentRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Date filter for appointments
    const dateFilter = document.getElementById('date-filter');
    
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            const selectedDate = this.value;
            
            // In a real application, this would likely submit a form or make an AJAX request
            if (selectedDate) {
                window.location.href = `appointments.php?date=${selectedDate}`;
            } else {
                window.location.href = 'appointments.php';
            }
        });
    }
    
    // Service filter for appointments
    const serviceFilter = document.getElementById('service-filter');
    
    if (serviceFilter) {
        serviceFilter.addEventListener('change', function() {
            const selectedService = this.value;
            
            // In a real application, this would likely submit a form or make an AJAX request
            if (selectedService) {
                window.location.href = `appointments.php?service=${selectedService}`;
            } else {
                window.location.href = 'appointments.php';
            }
        });
    }
    
    // Status filter for appointments
    const statusFilter = document.getElementById('status-filter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            
            // In a real application, this would likely submit a form or make an AJAX request
            if (selectedStatus) {
                window.location.href = `appointments.php?status=${selectedStatus}`;
            } else {
                window.location.href = 'appointments.php';
            }
        });
    }
    
    // Form validation for staff forms
    const staffForms = document.querySelectorAll('form.staff-form');
    
    staffForms.forEach(form => {
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
});