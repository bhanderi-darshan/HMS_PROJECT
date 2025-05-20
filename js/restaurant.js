document.addEventListener('DOMContentLoaded', function() {
    // Menu Category Filtering
    const categoryButtons = document.querySelectorAll('.category-btn');
    const menuItems = document.querySelectorAll('.menu-item');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            
            // Show all items if "All" category is selected
            if (category === 'all') {
                menuItems.forEach(item => {
                    item.style.display = 'flex';
                    
                    // Add fade-in animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.5s ease';
                        item.style.opacity = '1';
                    }, 10);
                });
            } else {
                // Filter items based on category
                menuItems.forEach(item => {
                    if (item.getAttribute('data-category') === category) {
                        item.style.display = 'flex';
                        
                        // Add fade-in animation
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.transition = 'opacity 0.5s ease';
                            item.style.opacity = '1';
                        }, 10);
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    });
    
    // Shopping Cart Functionality
    const cartToggle = document.querySelector('.cart-toggle');
    const cartClose = document.querySelector('.close-cart');
    const cart = document.querySelector('.shopping-cart');
    const cartOverlay = document.querySelector('.cart-overlay');
    const cartItems = document.querySelector('.cart-items');
    const totalAmount = document.querySelector('.total-amount');
    const itemCount = document.querySelector('.item-count');
    const emptyCartMessage = document.querySelector('.empty-cart-message');
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const checkoutBtn = document.querySelector('.checkout-btn');
    
    let cartProducts = [];
    
    // Open cart
    cartToggle.addEventListener('click', function() {
        cart.classList.add('open');
        cartOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
    
    // Close cart
    function closeCart() {
        cart.classList.remove('open');
        cartOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    
    cartClose.addEventListener('click', closeCart);
    cartOverlay.addEventListener('click', closeCart);
    
    // Add to cart
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            
            // Check if product already in cart
            const existingProductIndex = cartProducts.findIndex(item => item.id === productId);
            
            if (existingProductIndex !== -1) {
                // Update quantity if product already in cart
                cartProducts[existingProductIndex].quantity += 1;
            } else {
                // Add new product to cart
                cartProducts.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
            }
            
            // Update cart UI
            updateCart();
            
            // Show confirmation message
            const confirmMessage = document.createElement('div');
            confirmMessage.classList.add('add-confirmation');
            confirmMessage.textContent = `${productName} added to cart`;
            document.body.appendChild(confirmMessage);
            
            // Remove confirmation message after delay
            setTimeout(() => {
                confirmMessage.classList.add('show');
                setTimeout(() => {
                    confirmMessage.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(confirmMessage);
                    }, 300);
                }, 2000);
            }, 10);
        });
    });
    
    // Update cart UI
    function updateCart() {
        // Clear cart items
        cartItems.innerHTML = '';
        
        if (cartProducts.length === 0) {
            // Show empty cart message
            cartItems.innerHTML = '<div class="empty-cart-message">Your cart is empty</div>';
            itemCount.textContent = '0';
            totalAmount.textContent = '$0.00';
            return;
        }
        
        // Add cart items
        let total = 0;
        let count = 0;
        
        cartProducts.forEach(product => {
            const itemTotal = product.price * product.quantity;
            total += itemTotal;
            count += product.quantity;
            
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <div class="cart-item-details">
                    <div class="cart-item-name">${product.name}</div>
                    <div class="cart-item-price">$${product.price.toFixed(2)}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn decrease" data-id="${product.id}">-</button>
                    <input type="number" class="quantity-input" value="${product.quantity}" min="1" data-id="${product.id}" readOnly>
                    <button class="quantity-btn increase" data-id="${product.id}">+</button>
                </div>
                <button class="remove-item" data-id="${product.id}">×</button>
            `;
            
            cartItems.appendChild(cartItem);
        });
        
        // Update total and item count
        totalAmount.textContent = `$${total.toFixed(2)}`;
        itemCount.textContent = count.toString();
        
        // Add event listeners to quantity buttons and remove buttons
        const decreaseButtons = document.querySelectorAll('.decrease');
        const increaseButtons = document.querySelectorAll('.increase');
        const removeButtons = document.querySelectorAll('.remove-item');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        decreaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cartProducts.findIndex(item => item.id === productId);
                
                if (productIndex !== -1) {
                    if (cartProducts[productIndex].quantity > 1) {
                        cartProducts[productIndex].quantity -= 1;
                    } else {
                        cartProducts.splice(productIndex, 1);
                    }
                    
                    updateCart();
                }
            });
        });
        
        increaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cartProducts.findIndex(item => item.id === productId);
                
                if (productIndex !== -1) {
                    cartProducts[productIndex].quantity += 1;
                    updateCart();
                }
            });
        });
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cartProducts.findIndex(item => item.id === productId);
                
                if (productIndex !== -1) {
                    cartProducts.splice(productIndex, 1);
                    updateCart();
                }
            });
        });
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.getAttribute('data-id');
                const productIndex = cartProducts.findIndex(item => item.id === productId);
                const newQuantity = parseInt(this.value);
                
                if (productIndex !== -1 && newQuantity > 0) {
                    cartProducts[productIndex].quantity = newQuantity;
                    updateCart();
                }
            });
        });
    }
    
    // Checkout button
    checkoutBtn.addEventListener('click', function() {
        if (cartProducts.length === 0) {
            alert('Your cart is empty. Please add items before checking out.');
            return;
        }
        
        // In a real application, this would redirect to a checkout page
        alert('Proceeding to checkout...');
        
        // Clear cart after checkout
        cartProducts = [];
        updateCart();
        closeCart();
    });
    
    // Table Reservation Form Steps
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const formSteps = document.querySelectorAll('.form-step');
    
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const currentStepIndex = Array.from(formSteps).indexOf(currentStep);
            
            // Validate current step
            if (validateStep(currentStepIndex + 1)) {
                // Hide current step
                currentStep.classList.remove('active');
                
                // Show next step
                formSteps[currentStepIndex + 1].classList.add('active');
                
                // Update summary if moving to step 3
                if (currentStepIndex + 2 === 3) {
                    updateReservationSummary();
                }
            }
        });
    });
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const currentStepIndex = Array.from(formSteps).indexOf(currentStep);
            
            // Hide current step
            currentStep.classList.remove('active');
            
            // Show previous step
            formSteps[currentStepIndex - 1].classList.add('active');
        });
    });
    
    // Validate form steps
    function validateStep(step) {
        if (step === 1) {
            // Validate step 1 fields
            const fullName = document.getElementById('full_name');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const date = document.getElementById('reservation_date');
            const time = document.getElementById('reservation_time');
            
            if (!fullName.value.trim()) {
                alert('Please enter your full name');
                fullName.focus();
                return false;
            }
            
            if (!email.value.trim()) {
                alert('Please enter your email address');
                email.focus();
                return false;
            }
            
            if (!phone.value.trim()) {
                alert('Please enter your phone number');
                phone.focus();
                return false;
            }
            
            if (!date.value) {
                alert('Please select a date');
                date.focus();
                return false;
            }
            
            if (!time.value) {
                alert('Please select a time');
                time.focus();
                return false;
            }
            
            return true;
        } else if (step === 2) {
            // Validate table selection
            const tableId = document.getElementById('table_id');
            
            if (!tableId.value) {
                alert('Please select a table');
                return false;
            }
            
            return true;
        }
        
        return true;
    }
    
    // Table Selection
    const tables = document.querySelectorAll('.table-item');
    const tableId = document.getElementById('table_id');
    const noTableSelected = document.querySelector('.no-table-selected');
    const selectedTableInfo = document.querySelector('.selected-table-info');
    const selectedTableNumber = document.getElementById('selected-table-number');
    const selectedTableSeats = document.getElementById('selected-table-seats');
    
    tables.forEach(table => {
        table.addEventListener('click', function() {
            if (this.classList.contains('occupied')) {
                alert('This table is already occupied. Please select another table.');
                return;
            }
            
            // Remove selected class from all tables
            tables.forEach(t => t.classList.remove('selected'));
            
            // Add selected class to clicked table
            this.classList.add('selected');
            
            // Update hidden input value
            tableId.value = this.getAttribute('data-table-id');
            
            // Update table info display
            selectedTableNumber.textContent = this.getAttribute('data-table-id');
            selectedTableSeats.textContent = this.getAttribute('data-seats');
            
            // Show table info
            noTableSelected.style.display = 'none';
            selectedTableInfo.style.display = 'block';
        });
    });
    
    // Update reservation summary
    function updateReservationSummary() {
        const fullName = document.getElementById('full_name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const guests = document.getElementById('guests').value;
        const date = document.getElementById('reservation_date').value;
        const time = document.getElementById('reservation_time').value;
        const table = document.getElementById('table_id').value;
        const requests = document.getElementById('special_requests').value;
        
        document.getElementById('summary-name').textContent = fullName;
        document.getElementById('summary-email').textContent = email;
        document.getElementById('summary-phone').textContent = phone;
        document.getElementById('summary-guests').textContent = guests;
        document.getElementById('summary-date').textContent = formatDate(date);
        document.getElementById('summary-time').textContent = formatTime(time);
        document.getElementById('summary-table').textContent = `Table ${table}`;
        
        if (requests.trim()) {
            document.getElementById('summary-requests').textContent = requests;
            document.getElementById('summary-requests-container').style.display = 'flex';
        } else {
            document.getElementById('summary-requests-container').style.display = 'none';
        }
    }
    
    // Helper function to format date
    function formatDate(dateString) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }
    
    // Helper function to format time
    function formatTime(timeString) {
        // Convert 24-hour time to 12-hour format
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        
        return `${hour12}:${minutes} ${ampm}`;
    }
    
    // Reservation form submission
    const reservationForm = document.getElementById('reservationForm');
    
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // In a real application, this would submit the form to the server
            alert('Reservation request submitted successfully! We will confirm your reservation shortly.');
            
            // Reset form
            this.reset();
            
            // Return to step 1
            formSteps.forEach(step => step.classList.remove('active'));
            formSteps[0].classList.add('active');
            
            // Clear table selection
            tables.forEach(table => table.classList.remove('selected'));
            tableId.value = '';
            noTableSelected.style.display = 'block';
            selectedTableInfo.style.display = 'none';
        });
    }
    
    // Testimonial Slider
    const testimonials = document.querySelectorAll('.testimonial-slide');
    const prevTestimonialBtn = document.querySelector('.prev-testimonial');
    const nextTestimonialBtn = document.querySelector('.next-testimonial');
    let currentTestimonial = 0;
    
    // Hide all testimonials except the first one
    testimonials.forEach((testimonial, index) => {
        if (index !== 0) {
            testimonial.style.display = 'none';
        }
    });
    
    // Function to show a specific testimonial
    function showTestimonial(index) {
        testimonials.forEach(testimonial => {
            testimonial.style.display = 'none';
        });
        
        testimonials[index].style.display = 'block';
        testimonials[index].style.opacity = '0';
        
        // Fade in animation
        setTimeout(() => {
            testimonials[index].style.transition = 'opacity 0.5s ease';
            testimonials[index].style.opacity = '1';
        }, 50);
    }
    
    // Next testimonial button
    nextTestimonialBtn.addEventListener('click', function() {
        currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        showTestimonial(currentTestimonial);
    });
    
    // Previous testimonial button
    prevTestimonialBtn.addEventListener('click', function() {
        currentTestimonial = (currentTestimonial - 1 + testimonials.length) % testimonials.length;
        showTestimonial(currentTestimonial);
    });
    
    // Auto-advance testimonials every 5 seconds
    setInterval(function() {
        currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        showTestimonial(currentTestimonial);
    }, 5000);
    
    // Style for the cart confirmation message
    const style = document.createElement('style');
    style.textContent = `
        .add-confirmation {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background-color: var(--primary-color);
            color: var(--white);
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: var(--shadow-md);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .add-confirmation.show {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);
});