<?php
session_start();
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel - Welcome</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Experience Luxury Like Never Before</h1>
                <p>Your perfect getaway starts with an unforgettable stay</p>
                <a href="booking.php" class="btn btn-primary">Book Now</a>
            </div>
        </section>

        <!-- About Preview Section -->
        <section class="about-preview">
            <div class="container">
                <div class="about-content">
                    <h2>Welcome to Luxury Hotel</h2>
                    <p>Nestled in the heart of the city, our hotel combines elegant design with exceptional service to create an unforgettable experience for our guests.</p>
                    <p>With stunning views, world-class amenities, and a dedicated staff committed to your comfort, we invite you to discover why our hotel is the preferred choice for discerning travelers.</p>
                    <a href="about.php" class="btn btn-secondary">Learn More</a>
                </div>
                <div class="about-image">
                    <img src="https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Exterior">
                </div>
            </div>
        </section>
        
        <!-- Room Showcase -->
        <section class="room-showcase">
            <div class="container">
                <h2>Our Luxurious Rooms</h2>
                <div class="room-grid">
                    <?php
                    // Fetch featured rooms from database
                    $sql = "SELECT * FROM rooms WHERE featured = 1 LIMIT 3";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="room-card">';
                            echo '<div class="room-image">';
                            echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">';
                            echo '</div>';
                            echo '<div class="room-details">';
                            echo '<h3>' . $row['name'] . '</h3>';
                            echo '<p class="price">$' . $row['price'] . ' per night</p>';
                            echo '<p>' . substr($row['description'], 0, 100) . '...</p>';
                            echo '<div class="room-actions">';
                            echo '<a href="room-details.php?id=' . $row['id'] . '" class="btn btn-outline">View Details</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Fallback content if no rooms are in the database yet
                        ?>
                        <div class="room-card">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Deluxe Room">
                            </div>
                            <div class="room-details">
                                <h3>Deluxe Room</h3>
                                <p class="price">$199 per night</p>
                                <p>Spacious deluxe room with modern amenities and beautiful city view...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=1" class="btn btn-outline">View Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="room-card">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Executive Suite">
                            </div>
                            <div class="room-details">
                                <h3>Executive Suite</h3>
                                <p class="price">$299 per night</p>
                                <p>Luxurious suite with separate living area and premium amenities...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=2" class="btn btn-outline">View Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="room-card">
                            <div class="room-image">
                                <img src="https://images.pexels.com/photos/1838554/pexels-photo-1838554.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Presidential Suite">
                            </div>
                            <div class="room-details">
                                <h3>Presidential Suite</h3>
                                <p class="price">$499 per night</p>
                                <p>The ultimate luxury experience with panoramic views and exclusive services...</p>
                                <div class="room-actions">
                                    <a href="room-details.php?id=3" class="btn btn-outline">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="view-all">
                    <a href="rooms.php" class="btn btn-secondary">View All Rooms</a>
                </div>
            </div>
        </section>
        
        <!-- Services Preview -->
        <section class="services-preview">
            <div class="container">
                <h2>Our Premium Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3>Spa & Wellness</h3>
                        <p>Rejuvenate your body and mind with our therapeutic spa treatments.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Fine Dining</h3>
                        <p>Savor exquisite culinary creations from our world-class chefs.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h3>Fitness Center</h3>
                        <p>Stay fit with our state-of-the-art gym and wellness facilities.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-swimming-pool"></i>
                        </div>
                        <h3>Swimming Pool</h3>
                        <p>Relax by our luxurious infinity pool with stunning views.</p>
                    </div>
                </div>
                <div class="view-all">
                    <a href="services.php" class="btn btn-secondary">Explore All Services</a>
                </div>
            </div>
        </section>
        
        <!-- Testimonials Section -->
        <section class="testimonials">
            <div class="container">
                <h2>What Our Guests Say</h2>
                <div class="testimonial-slider">
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <p>"An absolutely amazing experience! The staff was attentive, the room was impeccable, and the amenities were top-notch. Will definitely be returning!"</p>
                            <div class="testimonial-author">
                                <h4>Sarah Johnson</h4>
                                <p>New York, USA</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <p>"The attention to detail is what sets this hotel apart. From the moment we arrived, we felt like royalty. The spa services were particularly exceptional."</p>
                            <div class="testimonial-author">
                                <h4>David Chen</h4>
                                <p>Toronto, Canada</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <p>"Our family vacation was perfect thanks to the wonderful accommodations and friendly staff. The kids loved the pool, and we enjoyed the restaurant's diverse menu."</p>
                            <div class="testimonial-author">
                                <h4>Emma Rodriguez</h4>
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
                    <h2>Ready for an Unforgettable Stay?</h2>
                    <p>Book your room today and experience luxury like never before.</p>
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script src="js/home.js"></script>
</body>
</html>