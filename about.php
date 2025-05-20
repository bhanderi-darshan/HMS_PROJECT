<?php
session_start();
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>About Us</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>About Us</span>
                </div>
            </div>
        </section>
        
        <!-- Our Story -->
        <section class="our-story">
            <div class="container">
                <div class="story-content">
                    <h2>Our Story</h2>
                    <p>Founded in 1995, Luxury Hotel has been setting the standard for premium hospitality for over 25 years. What began as a small boutique hotel has grown into a renowned destination for travelers seeking exceptional experiences.</p>
                    <p>Our journey has been guided by a single mission: to provide unparalleled service in an atmosphere of elegance and comfort. Through the years, we have remained committed to this vision, continuously evolving to meet the changing needs of our guests while preserving the timeless quality that has become our signature.</p>
                    <p>Today, Luxury Hotel stands as a testament to our dedication to excellence, welcoming guests from around the world to experience the perfect blend of traditional hospitality and modern luxury.</p>
                </div>
                <div class="story-image">
                    <img src="https://images.pexels.com/photos/261102/pexels-photo-261102.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel History">
                </div>
            </div>
        </section>
        
        <!-- Our Vision -->
        <section class="our-vision">
            <div class="container">
                <div class="vision-image">
                    <img src="https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Vision">
                </div>
                <div class="vision-content">
                    <h2>Our Vision</h2>
                    <p>At Luxury Hotel, we envision a world where exceptional hospitality creates memorable experiences that last a lifetime. Our vision is to be the preferred destination for discerning travelers who seek more than just accommodation, but a complete sensory journey.</p>
                    <p>We strive to redefine luxury by combining elegant surroundings with personalized service that anticipates and exceeds expectations. Our commitment extends beyond our guests to encompass our community and environment, ensuring that our operations are sustainable and beneficial to all stakeholders.</p>
                    <p>With each passing year, we renew our dedication to innovation and excellence, setting new standards in the hospitality industry while honoring the traditions that have made us who we are.</p>
                </div>
            </div>
        </section>
        
        <!-- Team Section -->
        <section class="our-team">
            <div class="container">
                <h2>Meet Our Team</h2>
                <div class="team-grid">
                    <div class="team-member fade-in">
                        <div class="member-image">
                            <img src="https://images.pexels.com/photos/5792641/pexels-photo-5792641.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Jonathan Pierce">
                        </div>
                        <div class="member-info">
                            <h3>Jonathan Pierce</h3>
                            <p class="member-title">General Manager</p>
                            <p>With over 20 years of experience in luxury hospitality, Jonathan leads our team with passion and dedication to excellence.</p>
                        </div>
                    </div>
                    <div class="team-member fade-in">
                        <div class="member-image">
                            <img src="https://images.pexels.com/photos/4117827/pexels-photo-4117827.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Sophia Chen">
                        </div>
                        <div class="member-info">
                            <h3>Sophia Chen</h3>
                            <p class="member-title">Executive Chef</p>
                            <p>Sophia brings creativity and expertise to our kitchen, crafting exquisite culinary experiences for our guests.</p>
                        </div>
                    </div>
                    <div class="team-member fade-in">
                        <div class="member-image">
                            <img src="https://images.pexels.com/photos/5668774/pexels-photo-5668774.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Marcus Johnson">
                        </div>
                        <div class="member-info">
                            <h3>Marcus Johnson</h3>
                            <p class="member-title">Concierge Manager</p>
                            <p>Marcus ensures that every guest request is fulfilled promptly, making every stay truly personalized.</p>
                        </div>
                    </div>
                    <div class="team-member fade-in">
                        <div class="member-image">
                            <img src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Olivia Martinez">
                        </div>
                        <div class="member-info">
                            <h3>Olivia Martinez</h3>
                            <p class="member-title">Spa Director</p>
                            <p>Olivia oversees our wellness offerings, creating rejuvenating experiences for mind, body, and soul.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Achievements -->
        <section class="achievements">
            <div class="container">
                <h2>Our Achievements</h2>
                <div class="achievements-grid">
                    <div class="achievement-item fade-in">
                        <div class="achievement-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Five-Star Excellence</h3>
                        <p>Awarded five stars by the International Hotel Association for 10 consecutive years.</p>
                    </div>
                    <div class="achievement-item fade-in">
                        <div class="achievement-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3>Culinary Excellence</h3>
                        <p>Our restaurant has earned a Michelin star for its exceptional cuisine and service.</p>
                    </div>
                    <div class="achievement-item fade-in">
                        <div class="achievement-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Green Certification</h3>
                        <p>Recognized for our sustainable practices and commitment to environmental responsibility.</p>
                    </div>
                    <div class="achievement-item fade-in">
                        <div class="achievement-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Guest Satisfaction</h3>
                        <p>Consistently ranked in the top 1% for guest satisfaction in independent surveys.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonials -->
        <section class="testimonials">
            <div class="container">
                <h2>What Our Guests Say</h2>
                <div class="testimonials-grid">
                    <div class="testimonial-item fade-in">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>The staff went above and beyond to make our anniversary special. The surprise champagne and rose petals in our room were such a thoughtful touch. We couldn't have asked for a more perfect stay.</p>
                            <div class="testimonial-author">
                                <h4>Robert & Lisa Thompson</h4>
                                <p>New York, USA</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item fade-in">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>As a frequent business traveler, I've stayed in many hotels, but Luxury Hotel truly stands out. The attention to detail, impeccable service, and comfortable accommodations make it my preferred choice whenever I'm in town.</p>
                            <div class="testimonial-author">
                                <h4>James Wilson</h4>
                                <p>London, UK</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item fade-in">
                        <div class="testimonial-content">
                            <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                            <p>Our family vacation was perfect thanks to the wonderful accommodations and friendly staff. The kids loved the pool, and we enjoyed the restaurant's diverse menu. We'll definitely be coming back!</p>
                            <div class="testimonial-author">
                                <h4>The Rodriguez Family</h4>
                                <p>Madrid, Spain</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Experience Luxury Hotel</h2>
                    <p>Book your stay today and discover why our guests return year after year.</p>
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>