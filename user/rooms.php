<?php
session_start();
include 'C:\xampp\htdocs\Finalpro\project\includes\config.php';

// Fetch all rooms from database
$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/rooms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'C:\xampp\htdocs\Finalpro\project\includes\header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Our Rooms</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Rooms</span>
                </div>
            </div>
        </section>
        
        <!-- Rooms Filter -->
        <section class="rooms-filter">
            <div class="container">
                <form action="rooms.php" method="get" class="filter-form">
                    <div class="filter-group">
                        <label for="type">Room Type</label>
                        <select name="type" id="type">
                            <option value="">All Types</option>
                            <option value="standard">Standard</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="suite">Suite</option>
                            <option value="executive">Executive</option>
                            <option value="presidential">Presidential</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="price_min">Min Price</label>
                        <input type="number" name="price_min" id="price_min" placeholder="Min Price">
                    </div>
                    <div class="filter-group">
                        <label for="price_max">Max Price</label>
                        <input type="number" name="price_max" id="price_max" placeholder="Max Price">
                    </div>
                    <div class="filter-group">
                        <label for="capacity">Capacity</label>
                        <select name="capacity" id="capacity">
                            <option value="">Any Capacity</option>
                            <option value="1">1 Person</option>
                            <option value="2">2 People</option>
                            <option value="3">3 People</option>
                            <option value="4">4+ People</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">Filter Rooms</button>
                    </div>
                </form>
            </div>
        </section>
        
        <!-- Rooms Grid -->
        <section class="rooms-grid">
            <div class="container">
                <div class="room-list">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $available = $row['is_available'] ? '<span class="status available">Available</span>' : '<span class="status unavailable">Unavailable</span>';
                            ?>
                            <div class="room-card fade-in">
                                <div class="room-image">
                                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                                    <?php echo $available; ?>
                                </div>
                                <div class="room-details">
                                    <h3><?php echo $row['name']; ?></h3>
                                    <p class="price">$<?php echo $row['price']; ?> per night</p>
                                    <div class="room-features">
                                        <span><i class="fas fa-user"></i> <?php echo $row['capacity']; ?> Guests</span>
                                        <span><i class="fas fa-bed"></i> <?php echo $row['bed_type']; ?></span>
                                        <span><i class="fas fa-vector-square"></i> <?php echo $row['size']; ?> m²</span>
                                    </div>
                                    <p class="room-description"><?php echo substr($row['description'], 0, 150); ?>...</p>
                                    <div class="room-actions">
                                        <a href="room-details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline">View Details</a>
                                        <?php if ($row['is_available']): ?>
                                            <a href="booking.php?room_id=<?php echo $row['id']; ?>" class="btn btn-primary">Book Now</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Fallback content if no rooms are in the database yet
                        ?>
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Deluxe Room">
                                <span class="status available">Available</span>
                            </div>
                            <div class="room-details">
                                <h3>Deluxe Room</h3>
                                <p class="price">$199 per night</p>
                                <div class="room-features">
                                    <span><i class="fas fa-user"></i> 2 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                    <span><i class="fas fa-vector-square"></i> 32 m²</span>
                                </div>
                                <p class="room-description">Spacious deluxe room with modern amenities and beautiful city view. Features include a luxurious bathroom, work desk, and high-speed internet access...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=1" class="btn btn-outline">View Details</a>
                                    <a href="booking.php?room_id=1" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Executive Suite">
                                <span class="status available">Available</span>
                            </div>
                            <div class="room-details">
                                <h3>Executive Suite</h3>
                                <p class="price">$299 per night</p>
                                <div class="room-features">
                                    <span><i class="fas fa-user"></i> 3 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                    <span><i class="fas fa-vector-square"></i> 48 m²</span>
                                </div>
                                <p class="room-description">Luxurious suite with separate living area and premium amenities. Enjoy a spacious bathroom with soaking tub, 24-hour room service, and exclusive access to the Executive Lounge...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=2" class="btn btn-outline">View Details</a>
                                    <a href="booking.php?room_id=2" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/1838554/pexels-photo-1838554.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Presidential Suite">
                                <span class="status available">Available</span>
                            </div>
                            <div class="room-details">
                                <h3>Presidential Suite</h3>
                                <p class="price">$499 per night</p>
                                <div class="room-features">
                                    <span><i class="fas fa-user"></i> 4 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                    <span><i class="fas fa-vector-square"></i> 72 m²</span>
                                </div>
                                <p class="room-description">The ultimate luxury experience with panoramic views and exclusive services. Features include a separate living room, dining area, private butler service, and premium amenities...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=3" class="btn btn-outline">View Details</a>
                                    <a href="booking.php?room_id=3" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/210265/pexels-photo-210265.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Family Suite">
                                <span class="status available">Available</span>
                            </div>
                            <div class="room-details">
                                <h3>Family Suite</h3>
                                <p class="price">$399 per night</p>
                                <div class="room-features">
                                    <span><i class="fas fa-user"></i> 5 Guests</span>
                                    <span><i class="fas fa-bed"></i> 2 King Beds</span>
                                    <span><i class="fas fa-vector-square"></i> 65 m²</span>
                                </div>
                                <p class="room-description">Perfect for families, this spacious suite offers two connecting rooms with all the amenities needed for a comfortable stay. Includes a mini-kitchen and family entertainment options...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=4" class="btn btn-outline">View Details</a>
                                    <a href="booking.php?room_id=4" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        
        <!-- Amenities Section -->
        <section class="room-amenities">
            <div class="container">
                <h2>Standard Room Amenities</h2>
                <div class="amenities-grid">
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <h3>High-Speed WiFi</h3>
                        <p>Stay connected with complimentary high-speed internet access in all rooms and public areas.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-tv"></i>
                        </div>
                        <h3>Smart TV</h3>
                        <p>Enjoy premium entertainment on a smart TV with access to streaming services.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-coffee"></i>
                        </div>
                        <h3>Coffee Maker</h3>
                        <p>Start your day right with our in-room coffee and tea making facilities.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-shower"></i>
                        </div>
                        <h3>Luxury Bathroom</h3>
                        <p>Pamper yourself with premium toiletries and plush towels in our elegant bathrooms.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-snowflake"></i>
                        </div>
                        <h3>Climate Control</h3>
                        <p>Personalize your comfort with individually controlled air conditioning and heating.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3>Room Service</h3>
                        <p>Enjoy 24-hour room service with a variety of dining options available.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-safe"></i>
                        </div>
                        <h3>In-Room Safe</h3>
                        <p>Keep your valuables secure in our electronic in-room safes.</p>
                    </div>
                    <div class="amenity-item fade-in">
                        <div class="amenity-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h3>Fitness Access</h3>
                        <p>Complimentary access to our state-of-the-art fitness center for all guests.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Experience Luxury?</h2>
                    <p>Book your perfect room today and enjoy a memorable stay with us.</p>
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>