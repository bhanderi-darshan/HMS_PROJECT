<?php
session_start();
include 'includes/config.php';

// Fetch services from database
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

$services = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Our Services</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Services</span>
                </div>
            </div>
        </section>
        
        <!-- Services Intro -->
        <section class="services-intro">
            <div class="container">
                <h2>Experience Luxury Beyond Accommodation</h2>
                <p class="intro-text">At Luxury Hotel, we go beyond providing exceptional accommodations to create a comprehensive luxury experience. Our carefully curated range of services is designed to elevate your stay, whether you're seeking relaxation, wellness, or convenience.</p>
                <p class="intro-text">Each of our services is delivered by skilled professionals committed to the highest standards of excellence, ensuring that every moment of your stay exceeds expectations.</p>
            </div>
        </section>
        
        <!-- Services List -->
        <section class="services-list">
            <div class="container">
                <?php
                if (count($services) > 0) {
                    foreach ($services as $index => $service) {
                        $isEven = $index % 2 == 0;
                        ?>
                        <div class="service-item <?php echo $isEven ? 'even' : 'odd'; ?>">
                            <div class="service-image">
                                <img src="<?php echo $service['image_url']; ?>" alt="<?php echo $service['name']; ?>">
                            </div>
                            <div class="service-details">
                                <h3><?php echo $service['name']; ?></h3>
                                <p class="service-description"><?php echo $service['description']; ?></p>
                                <div class="service-highlights">
                                    <h4>Highlights</h4>
                                    <ul>
                                        <?php
                                        $highlights = explode('|', $service['highlights']);
                                        foreach ($highlights as $highlight) {
                                            echo '<li><i class="fas fa-check"></i> ' . $highlight . '</li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="service-hours">
                                    <p><i class="far fa-clock"></i> <?php echo $service['hours']; ?></p>
                                </div>
                                <div class="service-price">
                                    <p>Starting at <span>$<?php echo $service['price']; ?></span></p>
                                </div>
                                <div class="service-actions">
                                    <a href="service-details.php?id=<?php echo $service['id']; ?>" class="btn btn-outline">View Details</a>
                                    <a href="service-booking.php?id=<?php echo $service['id']; ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Fallback services if none in database yet
                    ?>
                    <!-- Spa & Wellness -->
                    <div id="spa" class="service-item even">
                        <div class="service-image">
                            <img src="https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Spa & Wellness">
                        </div>
                        <div class="service-details">
                            <h3>Spa & Wellness</h3>
                            <p class="service-description">Indulge in a world of relaxation and rejuvenation at our award-winning spa. Our expert therapists combine ancient healing traditions with modern techniques to offer treatments that restore balance to body, mind, and spirit.</p>
                            <div class="service-highlights">
                                <h4>Highlights</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Signature massage therapies</li>
                                    <li><i class="fas fa-check"></i> Luxury facial treatments</li>
                                    <li><i class="fas fa-check"></i> Aromatherapy and body scrubs</li>
                                    <li><i class="fas fa-check"></i> Couples treatments</li>
                                    <li><i class="fas fa-check"></i> Hydrotherapy pool and steam room</li>
                                </ul>
                            </div>
                            <div class="service-hours">
                                <p><i class="far fa-clock"></i> Open daily: 9:00 AM - 8:00 PM</p>
                            </div>
                            <div class="service-price">
                                <p>Starting at <span>$85</span></p>
                            </div>
                            <div class="service-actions">
                                <a href="service-details.php?id=1" class="btn btn-outline">View Details</a>
                                <a href="service-booking.php?id=1" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fitness Center -->
                    <div id="fitness" class="service-item odd">
                        <div class="service-details">
                            <h3>Fitness Center</h3>
                            <p class="service-description">Maintain your fitness routine in our state-of-the-art fitness center, featuring premium cardio and strength training equipment. Our certified personal trainers are available to provide customized workout programs tailored to your goals.</p>
                            <div class="service-highlights">
                                <h4>Highlights</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Latest cardio and weight equipment</li>
                                    <li><i class="fas fa-check"></i> Personal training sessions</li>
                                    <li><i class="fas fa-check"></i> Yoga and pilates classes</li>
                                    <li><i class="fas fa-check"></i> Nutritional consultation</li>
                                    <li><i class="fas fa-check"></i> Fresh towels and water service</li>
                                </ul>
                            </div>
                            <div class="service-hours">
                                <p><i class="far fa-clock"></i> Open 24 hours for hotel guests</p>
                            </div>
                            <div class="service-price">
                                <p>Starting at <span>$45</span> for personal training</p>
                            </div>
                            <div class="service-actions">
                                <a href="service-details.php?id=2" class="btn btn-outline">View Details</a>
                                <a href="service-booking.php?id=2" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                        <div class="service-image">
                            <img src="https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Fitness Center">
                        </div>
                    </div>
                    
                    <!-- Swimming Pool -->
                    <div id="pool" class="service-item even">
                        <div class="service-image">
                            <img src="https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Swimming Pool">
                        </div>
                        <div class="service-details">
                            <h3>Swimming Pool</h3>
                            <p class="service-description">Dive into relaxation in our stunning infinity pool with panoramic views of the city skyline. The pool area features private cabanas, a poolside bar, and attentive service to enhance your experience.</p>
                            <div class="service-highlights">
                                <h4>Highlights</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Heated infinity pool</li>
                                    <li><i class="fas fa-check"></i> Poolside cabanas with reserved service</li>
                                    <li><i class="fas fa-check"></i> Jacuzzi and whirlpool</li>
                                    <li><i class="fas fa-check"></i> Pool bar with signature cocktails</li>
                                    <li><i class="fas fa-check"></i> Children's splash pool</li>
                                </ul>
                            </div>
                            <div class="service-hours">
                                <p><i class="far fa-clock"></i> Open daily: 7:00 AM - 10:00 PM</p>
                            </div>
                            <div class="service-price">
                                <p>Starting at <span>$65</span> for cabana reservation</p>
                            </div>
                            <div class="service-actions">
                                <a href="service-details.php?id=3" class="btn btn-outline">View Details</a>
                                <a href="service-booking.php?id=3" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Concierge Service -->
                    <div id="concierge" class="service-item odd">
                        <div class="service-details">
                            <h3>Concierge Service</h3>
                            <p class="service-description">Our dedicated concierge team is on hand to ensure your stay is effortless and exceptional. From restaurant reservations and tour bookings to special occasion arrangements, let us take care of all the details.</p>
                            <div class="service-highlights">
                                <h4>Highlights</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Personalized itinerary planning</li>
                                    <li><i class="fas fa-check"></i> Premium restaurant reservations</li>
                                    <li><i class="fas fa-check"></i> Ticket procurement for shows and events</li>
                                    <li><i class="fas fa-check"></i> Transportation arrangements</li>
                                    <li><i class="fas fa-check"></i> Special occasion surprises</li>
                                </ul>
                            </div>
                            <div class="service-hours">
                                <p><i class="far fa-clock"></i> Available 24/7</p>
                            </div>
                            <div class="service-price">
                                <p>Complimentary for all guests</p>
                            </div>
                            <div class="service-actions">
                                <a href="service-details.php?id=4" class="btn btn-outline">View Details</a>
                                <a href="service-booking.php?id=4" class="btn btn-primary">Request Service</a>
                            </div>
                        </div>
                        <div class="service-image">
                            <img src="https://images.pexels.com/photos/7061072/pexels-photo-7061072.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Concierge Service">
                        </div>
                    </div>
                    
                    <!-- Conference Facilities -->
                    <div id="conference" class="service-item even">
                        <div class="service-image">
                            <img src="https://images.pexels.com/photos/416320/pexels-photo-416320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Conference Facilities">
                        </div>
                        <div class="service-details">
                            <h3>Conference Facilities</h3>
                            <p class="service-description">Host impeccable meetings and events in our sophisticated conference facilities. With cutting-edge technology, versatile spaces, and dedicated event planners, we ensure your business functions run smoothly.</p>
                            <div class="service-highlights">
                                <h4>Highlights</h4>
                                <ul>
                                    <li><i class="fas fa-check"></i> Multiple conference rooms and ballrooms</li>
                                    <li><i class="fas fa-check"></i> State-of-the-art audiovisual equipment</li>
                                    <li><i class="fas fa-check"></i> High-speed internet access</li>
                                    <li><i class="fas fa-check"></i> Customized catering menus</li>
                                    <li><i class="fas fa-check"></i> Professional event planning assistance</li>
                                </ul>
                            </div>
                            <div class="service-hours">
                                <p><i class="far fa-clock"></i> Available upon request</p>
                            </div>
                            <div class="service-price">
                                <p>Starting at <span>$500</span> per half-day</p>
                            </div>
                            <div class="service-actions">
                                <a href="service-details.php?id=5" class="btn btn-outline">View Details</a>
                                <a href="service-booking.php?id=5" class="btn btn-primary">Inquire Now</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        
        <!-- Additional Services -->
        <section class="additional-services">
            <div class="container">
                <h2>Additional Services</h2>
                <div class="services-grid">
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h3>Airport Transfer</h3>
                        <p>Travel in comfort with our premium airport pickup and drop-off service. Our professional drivers will ensure a smooth journey to and from the airport.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h3>Childcare Services</h3>
                        <p>Enjoy your time while our qualified childcare professionals take care of your little ones with engaging activities and attentive supervision.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-suitcase"></i>
                        </div>
                        <h3>Luggage Storage</h3>
                        <p>Arriving early or departing late? Store your luggage securely and make the most of your time before check-in or after check-out.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h3>Laundry & Dry Cleaning</h3>
                        <p>Our premium laundry service ensures your clothes are immaculately cleaned and promptly returned to your room.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>In-Room Dining</h3>
                        <p>Enjoy culinary excellence in the privacy of your room with our 24-hour room service offering a wide selection of dishes and beverages.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>City Tours</h3>
                        <p>Discover the best of the city with our curated tours, led by knowledgeable local guides who share insights and hidden gems.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3>Gift Shop</h3>
                        <p>Browse our carefully selected collection of souvenirs, essentials, and luxury items at our onsite boutique.</p>
                    </div>
                    <div class="additional-service fade-in">
                        <div class="service-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Safety Deposit Box</h3>
                        <p>Keep your valuables secure with our complimentary in-room safety deposit boxes, providing peace of mind during your stay.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Spa Packages -->
        <section class="spa-packages">
            <div class="container">
                <h2>Featured Spa Packages</h2>
                <div class="packages-grid">
                    <div class="package-card fade-in">
                        <div class="package-image">
                            <img src="https://images.pexels.com/photos/3188/love-romantic-bath-candlelight.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Romantic Retreat">
                        </div>
                        <div class="package-details">
                            <h3>Romantic Retreat</h3>
                            <p class="package-duration"><i class="far fa-clock"></i> 2.5 hours</p>
                            <p class="package-description">A perfect couples experience featuring a side-by-side massage, champagne toast, and aromatherapy bath.</p>
                            <div class="package-includes">
                                <span>Includes:</span>
                                <ul>
                                    <li>Couples massage (60 min)</li>
                                    <li>Aromatherapy bath</li>
                                    <li>Foot reflexology</li>
                                    <li>Champagne and chocolates</li>
                                </ul>
                            </div>
                            <div class="package-price">
                                <span>$299</span> per couple
                            </div>
                            <a href="service-booking.php?id=1&package=romantic" class="btn btn-primary">Book This Package</a>
                        </div>
                    </div>
                    
                    <div class="package-card fade-in">
                        <div class="package-image">
                            <img src="https://images.pexels.com/photos/3865799/pexels-photo-3865799.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Total Rejuvenation">
                        </div>
                        <div class="package-details">
                            <h3>Total Rejuvenation</h3>
                            <p class="package-duration"><i class="far fa-clock"></i> 3 hours</p>
                            <p class="package-description">A comprehensive spa day that revitalizes your body from head to toe with our most popular treatments.</p>
                            <div class="package-includes">
                                <span>Includes:</span>
                                <ul>
                                    <li>Full body massage (60 min)</li>
                                    <li>Facial treatment</li>
                                    <li>Body scrub and wrap</li>
                                    <li>Scalp treatment</li>
                                    <li>Spa lunch</li>
                                </ul>
                            </div>
                            <div class="package-price">
                                <span>$350</span> per person
                            </div>
                            <a href="service-booking.php?id=1&package=rejuvenation" class="btn btn-primary">Book This Package</a>
                        </div>
                    </div>
                    
                    <div class="package-card fade-in">
                        <div class="package-image">
                            <img src="https://images.pexels.com/photos/3757952/pexels-photo-3757952.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Stress Relief">
                        </div>
                        <div class="package-details">
                            <h3>Stress Relief</h3>
                            <p class="package-duration"><i class="far fa-clock"></i> 2 hours</p>
                            <p class="package-description">Target tension and restore balance with this carefully designed package focused on stress reduction.</p>
                            <div class="package-includes">
                                <span>Includes:</span>
                                <ul>
                                    <li>Deep tissue massage (60 min)</li>
                                    <li>Hot stone therapy</li>
                                    <li>Aromatherapy</li>
                                    <li>Tension release facial</li>
                                </ul>
                            </div>
                            <div class="package-price">
                                <span>$230</span> per person
                            </div>
                            <a href="service-booking.php?id=1&package=stress" class="btn btn-primary">Book This Package</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Enhance Your Stay</h2>
                    <p>Book our premium services today to transform your stay into an extraordinary experience.</p>
                    <a href="service-booking.php" class="btn btn-primary">Book a Service</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>