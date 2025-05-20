<?php
session_start();
include 'includes/config.php';

// Get room ID from URL
$room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch room details
$sql = "SELECT * FROM rooms WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
} else {
    // If no room found with that ID, redirect to rooms page
    redirect('rooms.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $room['name']; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/room-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1><?php echo $room['name']; ?></h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <a href="rooms.php">Rooms</a> / <span><?php echo $room['name']; ?></span>
                </div>
            </div>
        </section>
        
        <!-- Room Details -->
        <section class="room-details-section">
            <div class="container">
                <div class="room-gallery">
                    <div class="main-image">
                        <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>" id="main-image">
                    </div>
                    <div class="thumbnail-gallery">
                        <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>" class="active" onclick="changeImage(this.src)">
                        <img src="https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="<?php echo $room['name']; ?> - View 2" onclick="changeImage(this.src)">
                        <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="<?php echo $room['name']; ?> - View 3" onclick="changeImage(this.src)">
                        <img src="https://images.pexels.com/photos/271631/pexels-photo-271631.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="<?php echo $room['name']; ?> - Bathroom" onclick="changeImage(this.src)">
                    </div>
                </div>
                
                <div class="room-info-booking">
                    <div class="room-info">
                        <div class="room-header">
                            <div>
                                <h2><?php echo $room['name']; ?></h2>
                                <div class="room-status">
                                    <?php if ($room['is_available']): ?>
                                        <span class="status available">Available</span>
                                    <?php else: ?>
                                        <span class="status unavailable">Unavailable</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="room-price">
                                <span>$<?php echo $room['price']; ?></span>
                                <span class="per-night">per night</span>
                            </div>
                        </div>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user"></i>
                                <span>
                                    <strong>Capacity</strong>
                                    <?php echo $room['capacity']; ?> Guests
                                </span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <span>
                                    <strong>Bed Type</strong>
                                    <?php echo $room['bed_type']; ?>
                                </span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-vector-square"></i>
                                <span>
                                    <strong>Size</strong>
                                    <?php echo $room['size']; ?> m²
                                </span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-mountain"></i>
                                <span>
                                    <strong>View</strong>
                                    City View
                                </span>
                            </div>
                        </div>
                        
                        <div class="room-description">
                            <h3>Description</h3>
                            <p><?php echo $room['description']; ?></p>
                        </div>
                        
                        <div class="room-amenities">
                            <h3>Amenities</h3>
                            <ul class="amenities-list">
                                <li><i class="fas fa-wifi"></i> Free WiFi</li>
                                <li><i class="fas fa-tv"></i> Smart TV</li>
                                <li><i class="fas fa-snowflake"></i> Air Conditioning</li>
                                <li><i class="fas fa-coffee"></i> Coffee Maker</li>
                                <li><i class="fas fa-bath"></i> Bathtub</li>
                                <li><i class="fas fa-concierge-bell"></i> Room Service</li>
                                <li><i class="fas fa-cocktail"></i> Mini Bar</li>
                                <li><i class="fas fa-safe"></i> In-room Safe</li>
                                <li><i class="fas fa-phone"></i> Direct Dial Telephone</li>
                                <li><i class="fas fa-shower"></i> Rain Shower</li>
                                <li><i class="fas fa-wind"></i> Hair Dryer</li>
                                <li><i class="fas fa-utensils"></i> Breakfast Included</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="booking-widget">
                        <h3>Book This Room</h3>
                        <form action="booking.php" method="get">
                            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                            
                            <div class="form-group">
                                <label for="check_in">Check In</label>
                                <input type="date" id="check_in" name="check_in" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="check_out">Check Out</label>
                                <input type="date" id="check_out" name="check_out" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="guests">Guests</label>
                                <select id="guests" name="guests" required>
                                    <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            
                            <div class="price-summary">
                                <div class="summary-item">
                                    <span>Base Price</span>
                                    <span>$<?php echo $room['price']; ?></span>
                                </div>
                                <div class="summary-item">
                                    <span>Taxes & Fees</span>
                                    <span>$<?php echo round($room['price'] * 0.12, 2); ?></span>
                                </div>
                                <div class="summary-total">
                                    <span>Total Per Night</span>
                                    <span>$<?php echo round($room['price'] * 1.12, 2); ?></span>
                                </div>
                            </div>
                            
                            <?php if ($room['is_available']): ?>
                                <button type="submit" class="btn btn-primary btn-block">Book Now</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-disabled btn-block" disabled>Currently Unavailable</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Room Policies -->
        <section class="room-policies">
            <div class="container">
                <h2>Room Policies</h2>
                <div class="policies-grid">
                    <div class="policy-item">
                        <div class="policy-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Check-in & Check-out</h3>
                        <p>Check-in: 3:00 PM - 12:00 AM</p>
                        <p>Check-out: Before 12:00 PM</p>
                    </div>
                    <div class="policy-item">
                        <div class="policy-icon">
                            <i class="fas fa-smoking-ban"></i>
                        </div>
                        <h3>Smoking Policy</h3>
                        <p>This is a non-smoking room. A cleaning fee of $250 will be charged for smoking in the room.</p>
                    </div>
                    <div class="policy-item">
                        <div class="policy-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <h3>Pet Policy</h3>
                        <p>Pets are not allowed in this room type. Please check our pet-friendly room options.</p>
                    </div>
                    <div class="policy-item">
                        <div class="policy-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h3>Child Policy</h3>
                        <p>Children of all ages are welcome. Children under 12 stay free when using existing bedding.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Similar Rooms -->
        <section class="similar-rooms">
            <div class="container">
                <h2>Similar Rooms</h2>
                <div class="room-grid">
                    <?php
                    // Get similar rooms (excluding current room)
                    $sql = "SELECT * FROM rooms WHERE id != ? ORDER BY RAND() LIMIT 3";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $room_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="room-card fade-in">
                                <div class="room-image">
                                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                                    <?php if ($row['is_available']): ?>
                                        <span class="status available">Available</span>
                                    <?php else: ?>
                                        <span class="status unavailable">Unavailable</span>
                                    <?php endif; ?>
                                </div>
                                <div class="room-details">
                                    <h3><?php echo $row['name']; ?></h3>
                                    <p class="price">$<?php echo $row['price']; ?> per night</p>
                                    <div class="room-features-small">
                                        <span><i class="fas fa-user"></i> <?php echo $row['capacity']; ?> Guests</span>
                                        <span><i class="fas fa-bed"></i> <?php echo $row['bed_type']; ?></span>
                                    </div>
                                    <div class="room-actions">
                                        <a href="room-details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Fallback similar rooms if none in database yet
                        ?>
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Deluxe Room">
                                <span class="status available">Available</span>
                            </div>
                            <div class="room-details">
                                <h3>Deluxe Room</h3>
                                <p class="price">$199 per night</p>
                                <div class="room-features-small">
                                    <span><i class="fas fa-user"></i> 2 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                </div>
                                <div class="room-actions">
                                    <a href="room-details.php?id=1" class="btn btn-outline">View Details</a>
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
                                <div class="room-features-small">
                                    <span><i class="fas fa-user"></i> 3 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                </div>
                                <div class="room-actions">
                                    <a href="room-details.php?id=2" class="btn btn-outline">View Details</a>
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
                                <div class="room-features-small">
                                    <span><i class="fas fa-user"></i> 4 Guests</span>
                                    <span><i class="fas fa-bed"></i> King Bed</span>
                                </div>
                                <div class="room-actions">
                                    <a href="room-details.php?id=3" class="btn btn-outline">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        function changeImage(src) {
            document.getElementById('main-image').src = src;
            
            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail-gallery img');
            thumbnails.forEach(thumbnail => {
                if (thumbnail.src === src) {
                    thumbnail.classList.add('active');
                } else {
                    thumbnail.classList.remove('active');
                }
            });
        }
        
        // Booking date validation
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            // Format the date as YYYY-MM-DD for the min attribute
            const nextDayFormatted = nextDay.toISOString().split('T')[0];
            checkOutInput.min = nextDayFormatted;
            
            // If the current check-out date is before the new min date, update it
            if (new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = nextDayFormatted;
            }
        });
    </script>
</body>
</html>