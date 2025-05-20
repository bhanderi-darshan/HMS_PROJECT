<?php
session_start();
include 'includes/config.php';

// Check if service_id is provided
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$package = isset($_GET['package']) ? clean_input($_GET['package']) : '';

// Check if user is logged in
if (!is_logged_in()) {
    // Store intended destination
    $_SESSION['redirect_after_login'] = "service-booking.php?id=$service_id" . ($package ? "&package=$package" : "");
    
    // Redirect to login
    redirect('login.php?message=Please log in to book a service');
}

// Get service details
$sql = "SELECT * FROM services WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $service = $result->fetch_assoc();
} else {
    // If no service found with that ID, use fallback data
    if ($service_id == 1) {
        $service = [
            'id' => 1,
            'name' => 'Spa & Wellness',
            'description' => 'Indulge in a world of relaxation and rejuvenation at our award-winning spa. Our expert therapists combine ancient healing traditions with modern techniques to offer treatments that restore balance to body, mind, and spirit.',
            'price' => '85',
            'hours' => 'Open daily: 9:00 AM - 8:00 PM',
            'image_url' => 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
        ];
    } elseif ($service_id == 2) {
        $service = [
            'id' => 2,
            'name' => 'Fitness Center',
            'description' => 'Maintain your fitness routine in our state-of-the-art fitness center, featuring premium cardio and strength training equipment. Our certified personal trainers are available to provide customized workout programs tailored to your goals.',
            'price' => '45',
            'hours' => 'Open 24 hours for hotel guests',
            'image_url' => 'https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
        ];
    } else {
        // If no specific fallback, redirect to services page
        redirect('services.php');
    }
}

// Set package information if selected
$package_info = [];
if ($package) {
    if ($package == 'romantic') {
        $package_info = [
            'name' => 'Romantic Retreat',
            'price' => 299,
            'duration' => '2.5 hours',
            'description' => 'A perfect couples experience featuring a side-by-side massage, champagne toast, and aromatherapy bath.'
        ];
    } elseif ($package == 'rejuvenation') {
        $package_info = [
            'name' => 'Total Rejuvenation',
            'price' => 350,
            'duration' => '3 hours',
            'description' => 'A comprehensive spa day that revitalizes your body from head to toe with our most popular treatments.'
        ];
    } elseif ($package == 'stress') {
        $package_info = [
            'name' => 'Stress Relief',
            'price' => 230,
            'duration' => '2 hours',
            'description' => 'Target tension and restore balance with this carefully designed package focused on stress reduction.'
        ];
    }
}

// Handle form submission
$booking_step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$booking_error = '';
$booking_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_booking'])) {
    // Process service booking
    $service_date = clean_input($_POST['service_date']);
    $service_time = clean_input($_POST['service_time']);
    $number_of_people = clean_input($_POST['number_of_people']);
    $special_requests = clean_input($_POST['special_requests']);
    $selected_package = clean_input($_POST['selected_package']);
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    
    // Basic validation
    if (empty($service_date) || empty($service_time) || empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
        $booking_error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $booking_error = 'Please enter a valid email address';
    } else {
        // Generate booking reference
        $booking_reference = generate_reference();
        
        // Set price based on package or standard service
        $booking_price = $selected_package ? $package_info['price'] : $service['price'] * $number_of_people;
        
        // In a real application, this would insert into a database
        // For this demo, we'll just simulate a successful booking
        $booking_success = true;
        $booking_step = 3; // Move to confirmation step
    }
}

// Available time slots
$time_slots = [
    '9:00', '9:30', '10:00', '10:30', '11:00', '11:30',
    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
    '18:00', '18:30', '19:00', '19:30'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/service-booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Book a Service</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <a href="services.php">Services</a> / <span>Booking</span>
                </div>
            </div>
        </section>
        
        <!-- Booking Process -->
        <section class="booking-process">
            <div class="container">
                <div class="booking-steps">
                    <div class="step <?php echo $booking_step >= 1 ? 'active' : ''; ?>">
                        <div class="step-number">1</div>
                        <div class="step-title">Select Service</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step <?php echo $booking_step >= 2 ? 'active' : ''; ?>">
                        <div class="step-number">2</div>
                        <div class="step-title">Personal Details</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step <?php echo $booking_step >= 3 ? 'active' : ''; ?>">
                        <div class="step-number">3</div>
                        <div class="step-title">Confirmation</div>
                    </div>
                </div>
                
                <?php if ($booking_error): ?>
                    <div class="error-message">
                        <?php echo $booking_error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($booking_success): ?>
                    <!-- Step 3: Booking Confirmation -->
                    <div class="booking-confirmation">
                        <div class="confirmation-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Booking Confirmed!</h2>
                        <p>Your service appointment has been successfully booked. We look forward to providing you with an exceptional experience.</p>
                        
                        <div class="confirmation-details">
                            <h3>Booking Details</h3>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Booking Reference:</span>
                                    <span class="detail-value"><?php echo $booking_reference; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Service:</span>
                                    <span class="detail-value">
                                        <?php echo $service['name']; ?>
                                        <?php if ($selected_package): ?>
                                            - <?php echo $package_info['name']; ?> Package
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date:</span>
                                    <span class="detail-value"><?php echo format_date($service_date); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Time:</span>
                                    <span class="detail-value"><?php echo $service_time; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Guests:</span>
                                    <span class="detail-value"><?php echo $number_of_people; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Total Amount:</span>
                                    <span class="detail-value">$<?php echo $booking_price; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="confirmation-note">
                            <h4>Important Information</h4>
                            <ul>
                                <li>Please arrive 15 minutes before your scheduled appointment time.</li>
                                <li>Cancellations must be made at least 24 hours in advance to avoid a cancellation fee.</li>
                                <li>A confirmation email has been sent to your registered email address.</li>
                            </ul>
                        </div>
                        
                        <div class="confirmation-actions">
                            <a href="user/bookings.php" class="btn btn-secondary">View My Bookings</a>
                            <a href="services.php" class="btn btn-primary">Browse More Services</a>
                        </div>
                    </div>
                <?php else: ?>
                    <form action="service-booking.php?id=<?php echo $service_id; ?>&step=<?php echo min($booking_step + 1, 3); ?><?php echo $package ? "&package=$package" : ""; ?>" method="post" class="booking-form">
                        <?php if ($booking_step == 1): ?>
                            <!-- Step 1: Select Service -->
                            <div class="booking-step-content">
                                <h2>Select Your Service</h2>
                                
                                <div class="selected-service">
                                    <div class="service-image">
                                        <img src="<?php echo $service['image_url']; ?>" alt="<?php echo $service['name']; ?>">
                                    </div>
                                    <div class="service-details">
                                        <h3><?php echo $service['name']; ?></h3>
                                        <?php if ($package && !empty($package_info)): ?>
                                            <p class="service-package"><?php echo $package_info['name']; ?> Package</p>
                                            <p class="service-price">$<?php echo $package_info['price']; ?></p>
                                            <p class="service-duration"><i class="far fa-clock"></i> <?php echo $package_info['duration']; ?></p>
                                            <p class="service-description"><?php echo $package_info['description']; ?></p>
                                            <input type="hidden" name="selected_package" value="<?php echo $package; ?>">
                                        <?php else: ?>
                                            <p class="service-price">Starting at $<?php echo $service['price']; ?> per person</p>
                                            <p class="service-hours"><i class="far fa-clock"></i> <?php echo $service['hours']; ?></p>
                                            <p class="service-description"><?php echo $service['description']; ?></p>
                                            <input type="hidden" name="selected_package" value="">
                                        <?php endif; ?>
                                        <a href="service-details.php?id=<?php echo $service_id; ?>" class="view-details">View Full Details</a>
                                    </div>
                                </div>
                                
                                <div class="booking-details">
                                    <h3>Appointment Details</h3>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="service_date">Date *</label>
                                            <input type="date" id="service_date" name="service_date" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="service_time">Time *</label>
                                            <select id="service_time" name="service_time" required>
                                                <option value="">Select Time</option>
                                                <?php foreach ($time_slots as $time): ?>
                                                    <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="number_of_people">Number of People *</label>
                                        <select id="number_of_people" name="number_of_people" required>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo $package && $package == 'romantic' && $i == 2 ? 'selected' : ''; ?>>
                                                    <?php echo $i; ?> <?php echo $i == 1 ? 'Person' : 'People'; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="special_requests">Special Requests</label>
                                        <textarea id="special_requests" name="special_requests" rows="3"></textarea>
                                        <p class="form-note">Please mention any preferences, health concerns, or special arrangements needed.</p>
                                    </div>
                                </div>
                                
                                <?php if (!$package): ?>
                                    <div class="price-estimate">
                                        <h3>Price Estimate</h3>
                                        <div class="estimate-calculation">
                                            <div class="estimate-row">
                                                <span>Service Fee</span>
                                                <span>$<?php echo $service['price']; ?> × <span id="people-count">1</span></span>
                                            </div>
                                            <div class="estimate-total">
                                                <span>Estimated Total</span>
                                                <span>$<span id="total-price"><?php echo $service['price']; ?></span></span>
                                            </div>
                                        </div>
                                        <p class="estimate-note">Final price may vary based on specific treatments selected.</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="booking-actions">
                                    <a href="services.php" class="btn btn-outline">Back to Services</a>
                                    <button type="submit" name="next_step" class="btn btn-primary">Continue to Personal Details</button>
                                </div>
                            </div>
                        <?php elseif ($booking_step == 2): ?>
                            <!-- Step 2: Personal Details -->
                            <div class="booking-step-content">
                                <h2>Your Details</h2>
                                
                                <div class="personal-form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="first_name">First Name *</label>
                                            <input type="text" id="first_name" name="first_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last Name *</label>
                                            <input type="text" id="last_name" name="last_name" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="email">Email Address *</label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone Number *</label>
                                            <input type="tel" id="phone" name="phone" required>
                                        </div>
                                    </div>
                                    
                                    <div class="health-questions">
                                        <h3>Health Information</h3>
                                        <p>This information helps us tailor our services to your specific needs and ensures your safety.</p>
                                        
                                        <div class="form-group checkbox-group">
                                            <input type="checkbox" id="health_conditions" name="health_conditions" value="1">
                                            <label for="health_conditions">Do you have any health conditions we should be aware of?</label>
                                        </div>
                                        <div class="health-conditions-details" style="display: none;">
                                            <div class="form-group">
                                                <textarea id="health_details" name="health_details" rows="3" placeholder="Please provide details of any health conditions or concerns"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group checkbox-group">
                                            <input type="checkbox" id="pregnancy" name="pregnancy" value="1">
                                            <label for="pregnancy">Are you pregnant?</label>
                                        </div>
                                        
                                        <div class="form-group checkbox-group">
                                            <input type="checkbox" id="allergies" name="allergies" value="1">
                                            <label for="allergies">Do you have any allergies to products or materials?</label>
                                        </div>
                                        <div class="allergies-details" style="display: none;">
                                            <div class="form-group">
                                                <textarea id="allergies_details" name="allergies_details" rows="3" placeholder="Please specify your allergies"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group checkbox-group">
                                        <input type="checkbox" id="terms_agree" name="terms_agree" required>
                                        <label for="terms_agree">I agree to the <a href="#" target="_blank">terms and conditions</a> and <a href="#" target="_blank">cancellation policy</a>.</label>
                                    </div>
                                </div>
                                
                                <div class="booking-summary">
                                    <h3>Booking Summary</h3>
                                    <div class="summary-details">
                                        <div class="summary-item">
                                            <span class="item-label">Service:</span>
                                            <span class="item-value">
                                                <?php echo $service['name']; ?>
                                                <?php if (isset($_POST['selected_package']) && $_POST['selected_package']): ?>
                                                    - <?php echo $package_info['name']; ?> Package
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Date:</span>
                                            <span class="item-value"><?php echo format_date($_POST['service_date']); ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Time:</span>
                                            <span class="item-value"><?php echo $_POST['service_time']; ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Guests:</span>
                                            <span class="item-value"><?php echo $_POST['number_of_people']; ?></span>
                                        </div>
                                        <?php if (isset($_POST['special_requests']) && $_POST['special_requests']): ?>
                                            <div class="summary-item">
                                                <span class="item-label">Special Requests:</span>
                                                <span class="item-value"><?php echo $_POST['special_requests']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="summary-item total-item">
                                            <span class="item-label">Estimated Total:</span>
                                            <span class="item-value">
                                                <?php
                                                if (isset($_POST['selected_package']) && $_POST['selected_package']) {
                                                    echo '$' . $package_info['price'];
                                                } else {
                                                    echo '$' . ($service['price'] * $_POST['number_of_people']);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hidden fields to maintain data between steps -->
                                <input type="hidden" name="service_date" value="<?php echo isset($_POST['service_date']) ? $_POST['service_date'] : ''; ?>">
                                <input type="hidden" name="service_time" value="<?php echo isset($_POST['service_time']) ? $_POST['service_time'] : ''; ?>">
                                <input type="hidden" name="number_of_people" value="<?php echo isset($_POST['number_of_people']) ? $_POST['number_of_people'] : '1'; ?>">
                                <input type="hidden" name="special_requests" value="<?php echo isset($_POST['special_requests']) ? $_POST['special_requests'] : ''; ?>">
                                <input type="hidden" name="selected_package" value="<?php echo isset($_POST['selected_package']) ? $_POST['selected_package'] : ''; ?>">
                                
                                <div class="booking-actions">
                                    <a href="service-booking.php?id=<?php echo $service_id; ?>&step=1<?php echo $package ? "&package=$package" : ""; ?>" class="btn btn-outline">Back</a>
                                    <button type="submit" name="submit_booking" class="btn btn-primary">Complete Booking</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Related Services -->
        <section class="related-services">
            <div class="container">
                <h2>You May Also Like</h2>
                <div class="services-grid">
                    <?php
                    // In a real application, fetch related services from database
                    // For this demo, we'll display static content
                    $related_services = [
                        [
                            'id' => $service_id == 1 ? 2 : 1,
                            'name' => $service_id == 1 ? 'Fitness Center' : 'Spa & Wellness',
                            'price' => $service_id == 1 ? '45' : '85',
                            'image_url' => $service_id == 1 ? 'https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1' : 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Swimming Pool',
                            'price' => '65',
                            'image_url' => 'https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Conference Facilities',
                            'price' => '500',
                            'image_url' => 'https://images.pexels.com/photos/416320/pexels-photo-416320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
                        ]
                    ];
                    
                    foreach ($related_services as $related_service):
                    ?>
                        <div class="related-service fade-in">
                            <div class="service-image">
                                <img src="<?php echo $related_service['image_url']; ?>" alt="<?php echo $related_service['name']; ?>">
                            </div>
                            <div class="service-info">
                                <h3><?php echo $related_service['name']; ?></h3>
                                <p class="service-price">Starting at $<?php echo $related_service['price']; ?></p>
                                <div class="service-actions">
                                    <a href="service-details.php?id=<?php echo $related_service['id']; ?>" class="btn btn-outline">View Details</a>
                                    <a href="service-booking.php?id=<?php echo $related_service['id']; ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Price calculation for step 1
            const numberSelect = document.getElementById('number_of_people');
            const peopleCount = document.getElementById('people-count');
            const totalPrice = document.getElementById('total-price');
            const basePrice = <?php echo isset($service['price']) ? $service['price'] : 0; ?>;
            
            if (numberSelect && peopleCount && totalPrice) {
                numberSelect.addEventListener('change', function() {
                    const count = parseInt(this.value);
                    peopleCount.textContent = count;
                    totalPrice.textContent = (basePrice * count).toFixed(2);
                });
            }
            
            // Toggle health details sections
            const healthConditionsCheckbox = document.getElementById('health_conditions');
            const healthDetailsSection = document.querySelector('.health-conditions-details');
            
            if (healthConditionsCheckbox && healthDetailsSection) {
                healthConditionsCheckbox.addEventListener('change', function() {
                    healthDetailsSection.style.display = this.checked ? 'block' : 'none';
                });
            }
            
            // Toggle allergies details section
            const allergiesCheckbox = document.getElementById('allergies');
            const allergiesDetailsSection = document.querySelector('.allergies-details');
            
            if (allergiesCheckbox && allergiesDetailsSection) {
                allergiesCheckbox.addEventListener('change', function() {
                    allergiesDetailsSection.style.display = this.checked ? 'block' : 'none';
                });
            }
        });
    </script>
</body>
</html>