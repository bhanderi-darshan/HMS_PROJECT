<?php
session_start();
include 'includes/config.php';

// Fetch menu items
$sql = "SELECT * FROM menu_items ORDER BY category";
$result = $conn->query($sql);

$menu_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/restaurant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Our Restaurant</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Restaurant</span>
                </div>
            </div>
        </section>
        
        <!-- Restaurant Intro -->
        <section class="restaurant-intro">
            <div class="container">
                <div class="intro-content">
                    <h2>Fine Dining Experience</h2>
                    <p>Indulge in an extraordinary culinary journey at our award-winning restaurant. Our talented chefs combine traditional techniques with innovative approaches to create dishes that are both familiar and surprising.</p>
                    <p>Using only the freshest seasonal ingredients sourced from local farmers and artisan producers, we offer a menu that changes with the seasons to provide a truly memorable dining experience.</p>
                    <div class="restaurant-hours">
                        <h3>Opening Hours</h3>
                        <ul>
                            <li><span>Breakfast:</span> 7:00 AM - 10:30 AM</li>
                            <li><span>Lunch:</span> 12:00 PM - 2:30 PM</li>
                            <li><span>Dinner:</span> 6:00 PM - 10:30 PM</li>
                            <li><span>Weekend Brunch:</span> 8:00 AM - 1:00 PM (Saturday & Sunday)</li>
                        </ul>
                    </div>
                    <div class="cta-buttons">
                        <a href="#menu" class="btn btn-secondary">View Menu</a>
                        <a href="#reserve-table" class="btn btn-primary">Reserve a Table</a>
                    </div>
                </div>
                <div class="intro-image">
                    <img src="https://images.pexels.com/photos/1307698/pexels-photo-1307698.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Restaurant Interior">
                </div>
            </div>
        </section>
        
        <!-- Restaurant Menu -->
        <section id="menu" class="restaurant-menu">
            <div class="container">
                <h2>Our Menu</h2>
                
                <div class="menu-categories">
                    <button class="category-btn active" data-category="all">All</button>
                    <button class="category-btn" data-category="starters">Starters</button>
                    <button class="category-btn" data-category="mains">Main Courses</button>
                    <button class="category-btn" data-category="desserts">Desserts</button>
                    <button class="category-btn" data-category="beverages">Beverages</button>
                </div>
                
                <div class="menu-items">
                    <?php
                    if (count($menu_items) > 0) {
                        foreach ($menu_items as $item) {
                            ?>
                            <div class="menu-item" data-category="<?php echo $item['category']; ?>">
                                <div class="menu-item-image">
                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                                <div class="menu-item-details">
                                    <div class="menu-item-header">
                                        <h3><?php echo $item['name']; ?></h3>
                                        <span class="menu-item-price">$<?php echo $item['price']; ?></span>
                                    </div>
                                    <p class="menu-item-description"><?php echo $item['description']; ?></p>
                                    <button class="btn btn-outline add-to-cart" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-price="<?php echo $item['price']; ?>">Add to Cart</button>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Fallback menu items if none in database yet
                        ?>
                        <!-- Starters -->
                        <div class="menu-item" data-category="starters">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/1211887/pexels-photo-1211887.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Herb-Crusted Prawns">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Herb-Crusted Prawns</h3>
                                    <span class="menu-item-price">$18</span>
                                </div>
                                <p class="menu-item-description">Succulent prawns coated in a fragrant herb crust, served with a zesty citrus aioli and microgreens.</p>
                                <button class="btn btn-outline add-to-cart" data-id="1" data-name="Herb-Crusted Prawns" data-price="18">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="starters">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/1893567/pexels-photo-1893567.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Beet & Goat Cheese Salad">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Beet & Goat Cheese Salad</h3>
                                    <span class="menu-item-price">$14</span>
                                </div>
                                <p class="menu-item-description">Roasted heirloom beets with creamy goat cheese, candied walnuts, and arugula, drizzled with honey balsamic reduction.</p>
                                <button class="btn btn-outline add-to-cart" data-id="2" data-name="Beet & Goat Cheese Salad" data-price="14">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="starters">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/539451/pexels-photo-539451.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Truffle Mushroom Soup">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Truffle Mushroom Soup</h3>
                                    <span class="menu-item-price">$12</span>
                                </div>
                                <p class="menu-item-description">Silky wild mushroom soup infused with truffle oil, garnished with crème fraîche and chives.</p>
                                <button class="btn btn-outline add-to-cart" data-id="3" data-name="Truffle Mushroom Soup" data-price="12">Add to Cart</button>
                            </div>
                        </div>
                        
                        <!-- Main Courses -->
                        <div class="menu-item" data-category="mains">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/769289/pexels-photo-769289.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Filet Mignon">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Filet Mignon</h3>
                                    <span class="menu-item-price">$42</span>
                                </div>
                                <p class="menu-item-description">8oz prime beef tenderloin grilled to perfection, served with truffle mashed potatoes, seasonal vegetables, and a red wine reduction.</p>
                                <button class="btn btn-outline add-to-cart" data-id="4" data-name="Filet Mignon" data-price="42">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="mains">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/725991/pexels-photo-725991.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Pan-Seared Sea Bass">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Pan-Seared Sea Bass</h3>
                                    <span class="menu-item-price">$36</span>
                                </div>
                                <p class="menu-item-description">Crispy-skinned sea bass on a bed of saffron risotto, accompanied by asparagus and lemon beurre blanc.</p>
                                <button class="btn btn-outline add-to-cart" data-id="5" data-name="Pan-Seared Sea Bass" data-price="36">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="mains">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/6896393/pexels-photo-6896393.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Wild Mushroom Risotto">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Wild Mushroom Risotto</h3>
                                    <span class="menu-item-price">$28</span>
                                </div>
                                <p class="menu-item-description">Creamy Arborio rice with a medley of wild mushrooms, finished with aged Parmesan, white truffle oil, and fresh herbs.</p>
                                <button class="btn btn-outline add-to-cart" data-id="6" data-name="Wild Mushroom Risotto" data-price="28">Add to Cart</button>
                            </div>
                        </div>
                        
                        <!-- Desserts -->
                        <div class="menu-item" data-category="desserts">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/3026804/pexels-photo-3026804.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chocolate Lava Cake">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Chocolate Lava Cake</h3>
                                    <span class="menu-item-price">$14</span>
                                </div>
                                <p class="menu-item-description">Warm chocolate cake with a molten center, served with vanilla bean ice cream and fresh berries.</p>
                                <button class="btn btn-outline add-to-cart" data-id="7" data-name="Chocolate Lava Cake" data-price="14">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="desserts">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/2144112/pexels-photo-2144112.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Crème Brûlée">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Crème Brûlée</h3>
                                    <span class="menu-item-price">$12</span>
                                </div>
                                <p class="menu-item-description">Classic vanilla custard with a caramelized sugar crust, accompanied by house-made shortbread cookies.</p>
                                <button class="btn btn-outline add-to-cart" data-id="8" data-name="Crème Brûlée" data-price="12">Add to Cart</button>
                            </div>
                        </div>
                        
                        <!-- Beverages -->
                        <div class="menu-item" data-category="beverages">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/2775860/pexels-photo-2775860.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Signature Martini">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Signature Martini</h3>
                                    <span class="menu-item-price">$16</span>
                                </div>
                                <p class="menu-item-description">Premium vodka or gin with house-infused vermouth and a twist of citrus. A classic with our special touch.</p>
                                <button class="btn btn-outline add-to-cart" data-id="9" data-name="Signature Martini" data-price="16">Add to Cart</button>
                            </div>
                        </div>
                        
                        <div class="menu-item" data-category="beverages">
                            <div class="menu-item-image">
                                <img src="https://images.pexels.com/photos/2531188/pexels-photo-2531188.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Artisanal Coffee Selection">
                            </div>
                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h3>Artisanal Coffee Selection</h3>
                                    <span class="menu-item-price">$8</span>
                                </div>
                                <p class="menu-item-description">Single-origin coffee prepared using your choice of method: French press, pour-over, or espresso. Served with petit fours.</p>
                                <button class="btn btn-outline add-to-cart" data-id="10" data-name="Artisanal Coffee Selection" data-price="8">Add to Cart</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        
        <!-- Shopping Cart -->
        <div class="shopping-cart">
            <div class="cart-header">
                <h3>Your Order</h3>
                <button class="close-cart">&times;</button>
            </div>
            <div class="cart-items">
                <!-- Cart items will be added here dynamically -->
                <div class="empty-cart-message">Your cart is empty</div>
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span class="total-amount">$0.00</span>
                </div>
                <button class="btn btn-primary checkout-btn">Proceed to Checkout</button>
            </div>
        </div>
        <div class="cart-overlay"></div>
        <button class="cart-toggle">
            <i class="fas fa-shopping-cart"></i>
            <span class="item-count">0</span>
        </button>
        
        <!-- Table Reservation -->
        <section id="reserve-table" class="table-reservation">
            <div class="container">
                <h2>Reserve a Table</h2>
                <div class="reservation-container">
                    <div class="reservation-image">
                        <img src="https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Restaurant Tables">
                    </div>
                    <div class="reservation-form">
                        <form action="restaurant-reserve.php" method="post" id="reservationForm">
                            <div class="form-step step-1 active">
                                <h3>Step 1: Your Details</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="full_name">Full Name *</label>
                                        <input type="text" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address *</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="tel" id="phone" name="phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="guests">Number of Guests *</label>
                                        <select id="guests" name="guests" required>
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?></option>
                                            <?php endfor; ?>
                                            <option value="more">More than 10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="reservation_date">Date *</label>
                                        <input type="date" id="reservation_date" name="reservation_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="reservation_time">Time *</label>
                                        <select id="reservation_time" name="reservation_time" required>
                                            <option value="">Select Time</option>
                                            <option value="12:00">12:00 PM</option>
                                            <option value="12:30">12:30 PM</option>
                                            <option value="13:00">1:00 PM</option>
                                            <option value="13:30">1:30 PM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="18:00">6:00 PM</option>
                                            <option value="18:30">6:30 PM</option>
                                            <option value="19:00">7:00 PM</option>
                                            <option value="19:30">7:30 PM</option>
                                            <option value="20:00">8:00 PM</option>
                                            <option value="20:30">8:30 PM</option>
                                            <option value="21:00">9:00 PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="special_requests">Special Requests</label>
                                    <textarea id="special_requests" name="special_requests" rows="3"></textarea>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary next-step">Continue to Select Table</button>
                                </div>
                            </div>
                            
                            <div class="form-step step-2">
                                <h3>Step 2: Select a Table</h3>
                                <div class="table-selection">
                                    <div class="restaurant-map">
                                        <div class="map-legends">
                                            <div class="legend-item">
                                                <span class="legend-color available"></span>
                                                <span>Available</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color selected"></span>
                                                <span>Selected</span>
                                            </div>
                                            <div class="legend-item">
                                                <span class="legend-color occupied"></span>
                                                <span>Occupied</span>
                                            </div>
                                        </div>
                                        <div class="floor-plan">
                                            <!-- Tables will be dynamically generated here -->
                                            <div class="restaurant-section entrance">
                                                <span>Entrance</span>
                                            </div>
                                            <div class="restaurant-section bar">
                                                <span>Bar</span>
                                            </div>
                                            <div class="restaurant-section kitchen">
                                                <span>Kitchen</span>
                                            </div>
                                            <div class="tables-grid">
                                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                                    <div class="table-item <?php echo rand(0, 10) > 6 ? 'occupied' : 'available'; ?>" data-table-id="<?php echo $i; ?>" data-seats="<?php echo ($i % 2 == 0) ? 4 : 2; ?>">
                                                        <span class="table-number"><?php echo $i; ?></span>
                                                        <span class="table-seats"><?php echo ($i % 2 == 0) ? '4' : '2'; ?> Seats</span>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-info">
                                        <h4>Selected Table</h4>
                                        <div class="no-table-selected">
                                            <p>Please select a table from the floor plan.</p>
                                        </div>
                                        <div class="selected-table-info" style="display: none;">
                                            <p><strong>Table Number:</strong> <span id="selected-table-number"></span></p>
                                            <p><strong>Seats:</strong> <span id="selected-table-seats"></span></p>
                                            <p><strong>Location:</strong> <span id="selected-table-location">Main Dining Area</span></p>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="table_id" id="table_id" value="">
                                <div class="form-actions">
                                    <button type="button" class="btn btn-outline prev-step">Back</button>
                                    <button type="button" class="btn btn-primary next-step">Continue to Confirmation</button>
                                </div>
                            </div>
                            
                            <div class="form-step step-3">
                                <h3>Step 3: Confirm Reservation</h3>
                                <div class="reservation-summary">
                                    <h4>Reservation Details</h4>
                                    <div class="summary-details">
                                        <div class="summary-item">
                                            <span class="item-label">Name:</span>
                                            <span class="item-value" id="summary-name"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Email:</span>
                                            <span class="item-value" id="summary-email"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Phone:</span>
                                            <span class="item-value" id="summary-phone"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Guests:</span>
                                            <span class="item-value" id="summary-guests"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Date:</span>
                                            <span class="item-value" id="summary-date"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Time:</span>
                                            <span class="item-value" id="summary-time"></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Table:</span>
                                            <span class="item-value" id="summary-table"></span>
                                        </div>
                                        <div class="summary-item" id="summary-requests-container" style="display: none;">
                                            <span class="item-label">Special Requests:</span>
                                            <span class="item-value" id="summary-requests"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="confirmation-policy">
                                    <h4>Reservation Policy</h4>
                                    <ul>
                                        <li>We hold reservations for 15 minutes past the reserved time.</li>
                                        <li>For cancellations, please notify us at least 24 hours in advance.</li>
                                        <li>For parties of 6 or more, a credit card is required to hold the reservation.</li>
                                    </ul>
                                    <div class="form-group checkbox-group">
                                        <input type="checkbox" id="policy_agree" name="policy_agree" required>
                                        <label for="policy_agree">I agree to the reservation policy</label>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-outline prev-step">Back</button>
                                    <button type="submit" class="btn btn-primary">Confirm Reservation</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Chef Spotlight -->
        <section class="chef-spotlight">
            <div class="container">
                <h2>Meet Our Chef</h2>
                <div class="chef-profile">
                    <div class="chef-image">
                        <img src="https://images.pexels.com/photos/7226378/pexels-photo-7226378.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chef Sophia Chen">
                    </div>
                    <div class="chef-bio">
                        <h3>Sophia Chen</h3>
                        <p class="chef-title">Executive Chef</p>
                        <div class="chef-story">
                            <p>Chef Sophia Chen brings over 15 years of culinary expertise to our kitchen. After training at the prestigious Culinary Institute of America and working in Michelin-starred restaurants across Europe and Asia, Chef Chen has developed a unique cooking style that blends classical techniques with innovative approaches.</p>
                            <p>Her philosophy centers on showcasing the natural flavors of seasonal ingredients while adding unexpected twists that surprise and delight. Under her leadership, our restaurant has earned numerous accolades, including a coveted Michelin star.</p>
                            <p>"My goal is to create memorable dining experiences that tell a story through each dish," says Chef Chen. "I want our guests to feel both comforted and excited by the food we serve."</p>
                        </div>
                        <div class="chef-signature">
                            <p>Signature Dish: <span>Truffle-Infused Mushroom Risotto with 63°C Egg</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonials -->
        <section class="testimonials">
            <div class="container">
                <h2>Guest Reviews</h2>
                <div class="testimonials-slider">
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>"An extraordinary dining experience from start to finish. The tasting menu was a revelation, with each course more impressive than the last. The sommelier's wine pairings were inspired, and the service was impeccable. This restaurant alone is worth planning a trip around."</p>
                            <div class="testimonial-author">
                                <h4>James Wilson</h4>
                                <p>Food Critic, The Culinary Review</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>"We celebrated our anniversary here and couldn't have chosen a better place. The ambiance was romantic, the food was exceptional, and the staff made us feel special. The chef even prepared a personalized dessert for our celebration. Truly a memorable evening."</p>
                            <div class="testimonial-author">
                                <h4>Robert & Emily Thompson</h4>
                                <p>New York, USA</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>"As someone with dietary restrictions, I often find dining out challenging. The chef here not only accommodated my needs but created a special menu that was just as innovative and delicious as the regular offerings. This level of care and creativity is rare and deeply appreciated."</p>
                            <div class="testimonial-author">
                                <h4>Maria Rodriguez</h4>
                                <p>Madrid, Spain</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-controls">
                    <button class="prev-testimonial"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-testimonial"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Reserve Your Table Today</h2>
                    <p>Experience exceptional cuisine in an elegant setting. Book your table now and create memories that will last a lifetime.</p>
                    <a href="#reserve-table" class="btn btn-primary">Make a Reservation</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script src="js/restaurant.js"></script>
</body>
</html>