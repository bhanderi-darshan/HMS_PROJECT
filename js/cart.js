document.addEventListener('DOMContentLoaded', function() {
    // Cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartToggle = document.querySelector('.cart-toggle');
    const cartOverlay = document.querySelector('.cart-overlay');
    const cartClose = document.querySelector('.close-cart');
    const cartItemsList = document.querySelector('.cart-items');
    const cartTotal = document.querySelector('.total-amount');
    const itemCount = document.querySelector('.item-count');
    
    // Add to cart
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemPrice = parseFloat(this.dataset.price);
            
            // Send AJAX request to add item to cart
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    item_id: itemId,
                    name: itemName,
                    price: itemPrice
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCart();
                    showNotification('Item added to cart');
                }
            });
        });
    });
    
    // Update cart
    function updateCart() {
        fetch('get-cart.php')
            .then(response => response.json())
            .then(data => {
                // Update cart items
                cartItemsList.innerHTML = '';
                let count = 0;
                
                Object.keys(data.items).forEach(itemId => {
                    const item = data.items[itemId];
                    count += item.quantity;
                    
                    const cartItem = document.createElement('div');
                    cartItem.className = 'cart-item';
                    cartItem.innerHTML = `
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="quantity-btn decrease" data-id="${itemId}">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${itemId}" readonly>
                            <button class="quantity-btn increase" data-id="${itemId}">+</button>
                        </div>
                        <button class="remove-item" data-id="${itemId}">×</button>
                    `;
                    
                    cartItemsList.appendChild(cartItem);
                });
                
                // Update total and count
                cartTotal.textContent = `$${data.total.toFixed(2)}`;
                itemCount.textContent = count;
                
                // Show empty cart message if needed
                if (count === 0) {
                    cartItemsList.innerHTML = '<div class="empty-cart-message">Your cart is empty</div>';
                }
                
                // Add event listeners to new buttons
                addCartButtonListeners();
            });
    }
    
    // Cart button listeners
    function addCartButtonListeners() {
        // Quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const isIncrease = this.classList.contains('increase');
                const input = this.parentElement.querySelector('.quantity-input');
                let quantity = parseInt(input.value);
                
                if (isIncrease) {
                    quantity++;
                } else if (quantity > 1) {
                    quantity--;
                }
                
                updateCartQuantity(itemId, quantity);
            });
        });
        
        // Remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                removeFromCart(itemId);
            });
        });
    }
    
    // Update quantity
    function updateCartQuantity(itemId, quantity) {
        fetch('update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCart();
            }
        });
    }
    
    // Remove from cart
    function removeFromCart(itemId) {
        fetch('remove-from-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                item_id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCart();
                showNotification('Item removed from cart');
            }
        });
    }
    
    // Toggle cart
    if (cartToggle) {
        cartToggle.addEventListener('click', function() {
            document.querySelector('.shopping-cart').classList.add('open');
            cartOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (cartClose) {
        cartClose.addEventListener('click', closeCart);
    }
    
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCart);
    }
    
    function closeCart() {
        document.querySelector('.shopping-cart').classList.remove('open');
        cartOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    
    // Notification
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 2000);
        }, 100);
    }
    
    // Initial cart load
    updateCart();
});