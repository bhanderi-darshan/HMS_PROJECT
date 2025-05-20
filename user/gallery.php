<?php
session_start();
include 'C:\xampp\htdocs\Finalpro\project\includes\config.php';

// Fetch gallery categories
$categories = [
    'all' => 'All',
    'rooms' => 'Rooms & Suites',
    'dining' => 'Dining',
    'spa' => 'Spa & Wellness',
    'events' => 'Events & Celebrations',
    'amenities' => 'Amenities',
    'exterior' => 'Exterior & Surroundings'
];

// Fetch gallery images
$gallery_images = [
    [
        'id' => 1,
        'title' => 'Luxury Suite',
        'category' => 'rooms',
        'image_url' => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Spacious suite with modern amenities and elegant design.'
    ],
    [
        'id' => 2,
        'title' => 'Presidential Suite',
        'category' => 'rooms',
        'image_url' => 'https://images.pexels.com/photos/1579253/pexels-photo-1579253.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Our most luxurious accommodation with breathtaking city views.'
    ],
    [
        'id' => 3,
        'title' => 'Deluxe Twin Room',
        'category' => 'rooms',
        'image_url' => 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Comfortable twin beds in a beautifully decorated room.'
    ],
    [
        'id' => 4,
        'title' => 'Executive Bathroom',
        'category' => 'rooms',
        'image_url' => 'https://images.pexels.com/photos/1457847/pexels-photo-1457847.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Marble bathroom with premium fixtures and amenities.'
    ],
    [
        'id' => 5,
        'title' => 'Fine Dining Restaurant',
        'category' => 'dining',
        'image_url' => 'https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Elegant restaurant offering gourmet cuisine and fine wines.'
    ],
    [
        'id' => 6,
        'title' => 'Signature Dish',
        'category' => 'dining',
        'image_url' => 'https://images.pexels.com/photos/299347/pexels-photo-299347.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Chef\'s specialty prepared with the finest ingredients.'
    ],
    [
        'id' => 7,
        'title' => 'Cocktail Bar',
        'category' => 'dining',
        'image_url' => 'https://images.pexels.com/photos/3201920/pexels-photo-3201920.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Stylish bar serving innovative cocktails and premium spirits.'
    ],
    [
        'id' => 8,
        'title' => 'Breakfast Buffet',
        'category' => 'dining',
        'image_url' => 'https://images.pexels.com/photos/5175515/pexels-photo-5175515.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Start your day with our extensive breakfast selection.'
    ],
    [
        'id' => 9,
        'title' => 'Spa Treatment Room',
        'category' => 'spa',
        'image_url' => 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Tranquil space for rejuvenating spa treatments.'
    ],
    [
        'id' => 10,
        'title' => 'Indoor Pool',
        'category' => 'amenities',
        'image_url' => 'https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Temperature-controlled pool for year-round swimming.'
    ],
    [
        'id' => 11,
        'title' => 'Fitness Center',
        'category' => 'amenities',
        'image_url' => 'https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'State-of-the-art equipment for maintaining your fitness routine.'
    ],
    [
        'id' => 12,
        'title' => 'Wedding Reception',
        'category' => 'events',
        'image_url' => 'https://images.pexels.com/photos/3585798/pexels-photo-3585798.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Elegant venue for your special day.'
    ],
    [
        'id' => 13,
        'title' => 'Conference Room',
        'category' => 'events',
        'image_url' => 'https://images.pexels.com/photos/416320/pexels-photo-416320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Professional setting for successful business meetings.'
    ],
    [
        'id' => 14,
        'title' => 'Hotel Exterior',
        'category' => 'exterior',
        'image_url' => 'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Impressive architecture and welcoming entrance.'
    ],
    [
        'id' => 15,
        'title' => 'Garden Terrace',
        'category' => 'exterior',
        'image_url' => 'https://images.pexels.com/photos/631477/pexels-photo-631477.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Beautifully landscaped outdoor space for relaxation.'
    ],
    [
        'id' => 16,
        'title' => 'Rooftop View',
        'category' => 'exterior',
        'image_url' => 'https://images.pexels.com/photos/2034335/pexels-photo-2034335.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'description' => 'Panoramic views of the city skyline.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="C:\xampp\htdocs\Finalpro\project\css\gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'C:\xampp\htdocs\Finalpro\project\includes\header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Photo Gallery</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Gallery</span>
                </div>
            </div>
        </section>
        
        <!-- Gallery Section -->
        <section class="gallery-section">
            <div class="container">
                <div class="gallery-intro">
                    <h2>Explore Our Hotel</h2>
                    <p>Take a visual journey through our luxurious hotel spaces, elegant accommodations, and exceptional amenities. Each image offers a glimpse into the unparalleled experience awaiting our guests.</p>
                </div>
                
                <div class="gallery-filter">
                    <div class="filter-categories">
                        <?php foreach ($categories as $key => $name): ?>
                            <button class="filter-btn <?php echo $key === 'all' ? 'active' : ''; ?>" data-category="<?php echo $key; ?>"><?php echo $name; ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="gallery-grid">
                    <?php foreach ($gallery_images as $image): ?>
                        <div class="gallery-item fade-in" data-category="<?php echo $image['category']; ?>">
                            <div class="gallery-image">
                                <img src="<?php echo $image['image_url']; ?>" alt="<?php echo $image['title']; ?>" loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="overlay-content">
                                        <h3><?php echo $image['title']; ?></h3>
                                        <p><?php echo $image['description']; ?></p>
                                        <button class="view-full" data-id="<?php echo $image['id']; ?>">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Lightbox Modal -->
                <div class="lightbox">
                    <div class="lightbox-content">
                        <button class="lightbox-close">&times;</button>
                        <img src="" alt="" class="lightbox-image">
                        <div class="lightbox-caption">
                            <h3 class="lightbox-title"></h3>
                            <p class="lightbox-description"></p>
                        </div>
                        <button class="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
                        <button class="lightbox-next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Virtual Tour Section -->
        <section class="virtual-tour">
            <div class="container">
                <div class="tour-content">
                    <h2>Take a Virtual Tour</h2>
                    <p>Experience our hotel from the comfort of your home with our interactive virtual tour. Explore our rooms, facilities, and public spaces to get a feel for what awaits you during your stay.</p>
                    <a href="#" class="btn btn-primary">Start Virtual Tour</a>
                </div>
                <div class="tour-image">
                    <img src="https://images.pexels.com/photos/2034335/pexels-photo-2034335.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Virtual Tour">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Social Media Gallery -->
        <section class="social-gallery">
            <div class="container">
                <h2>Follow Us on Instagram</h2>
                <p class="social-intro">Share your moments with us using <span>#LuxuryHotelExperience</span></p>
                
                <div class="instagram-grid">
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/669992/pexels-photo-669992.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/2736387/pexels-photo-2736387.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/347141/pexels-photo-347141.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                    <div class="instagram-item">
                        <img src="https://images.pexels.com/photos/2403017/pexels-photo-2403017.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Instagram Post">
                        <div class="instagram-overlay">
                            <i class="fab fa-instagram"></i>
                        </div>
                    </div>
                </div>
                
                <div class="social-cta">
                    <a href="https://www.instagram.com" target="_blank" class="btn btn-outline">
                        <i class="fab fa-instagram"></i> Follow us on Instagram
                    </a>
                </div>
            </div>
        </section>
        
        <!-- Video Gallery -->
        <section class="video-gallery">
            <div class="container">
                <h2>Video Highlights</h2>
                <p class="video-intro">Discover our hotel through captivating videos that showcase our exceptional services and facilities.</p>
                
                <div class="video-grid">
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Tour">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3>Hotel Tour</h3>
                            <p>A comprehensive tour of our luxury hotel facilities.</p>
                        </div>
                    </div>
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="https://images.pexels.com/photos/1579253/pexels-photo-1579253.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Room Showcase">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3>Room Showcase</h3>
                            <p>Explore our elegantly designed rooms and suites.</p>
                        </div>
                    </div>
                    <div class="video-item">
                        <div class="video-thumbnail">
                            <img src="https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Dining Experience">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3>Dining Experience</h3>
                            <p>Discover the culinary delights at our restaurants.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Experience the Luxury Firsthand</h2>
                    <p>Ready to experience all you've seen? Book your stay today and create your own memories.</p>
                    <a href="booking.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script src="js/gallery.js"></script>
</body>
</html>