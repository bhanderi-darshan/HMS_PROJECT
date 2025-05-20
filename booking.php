<?php
session_start();
// Make sure the path to config.php is correct
require_once 'includes/config.php';

// Check if database connection is established
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . ($conn->connect_error ?? "Unknown error"));
}

// Check if room_id is provided
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

// Check if user is logged in - Make sure this function exists in your includes
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

// Check if redirect function exists
if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

// Check if user is logged in
if (!is_logged_in()) {
    // Store intended destination
    $_SESSION['redirect_after_login'] = "booking.php?room_id=$room_id";
    
    // Redirect to login
    redirect('login.php?message=Please log in to book a room');
}

// Get room details
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

// Check if the room is available
if (!$room['is_available']) {
    redirect('rooms.php?message=This room is currently unavailable');
}

// Get booking parameters from URL if they exist
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : date('Y-m-d');
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : date('Y-m-d', strtotime('+1 day'));
$guests = isset($_GET['guests']) ? intval($_GET['guests']) : 1;

// Calculate number of nights and total price
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$nights = $check_in_date->diff($check_out_date)->days;
$base_price = $room['price'] * $nights;
$tax = $base_price * 0.12; // 12% tax
$total = $base_price + $tax;

// Check if clean_input function exists
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

// Check if format_date function exists
if (!function_exists('format_date')) {
    function format_date($date_string) {
        $date = new DateTime($date_string);
        return $date->format('F j, Y');
    }
}

// Check if format_price function exists
if (!function_exists('format_price')) {
    function format_price($price) {
        return '$' . number_format($price, 2);
    }
}

// Check if generate_reference function exists
if (!function_exists('generate_reference')) {
    function generate_reference() {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }
}

// Handle form submission
$booking_step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$booking_error = '';
$booking_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_booking'])) {
    // Process final booking
    $first_name = isset($_POST['first_name']) ? clean_input($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? clean_input($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? clean_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? clean_input($_POST['phone']) : '';
    $special_requests = isset($_POST['special_requests']) ? clean_input($_POST['special_requests']) : '';
    $payment_method = isset($_POST['payment_method']) ? clean_input($_POST['payment_method']) : '';
    
    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
        $booking_error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $booking_error = 'Please enter a valid email address';
    } else {
        // Generate booking reference
        $booking_reference = generate_reference();
        
        // Insert booking into database
        $sql = "INSERT INTO bookings 
                (user_id, room_id, booking_reference, check_in_date, check_out_date, 
                num_guests, total_price, first_name, last_name, email, phone, 
                special_requests, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssidsssss",
            $_SESSION['user_id'], $room_id, $booking_reference, $check_in, $check_out,
            $guests, $total, $first_name, $last_name, $email, $phone,
            $special_requests, $payment_method
        );
        
        if ($stmt->execute()) {
            // Update room availability
            $update_sql = "UPDATE rooms SET is_available = 0 WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $room_id);
            $update_stmt->execute();
            
            // Set success flag
            $booking_success = true;
            $booking_step = 4; // Move to confirmation step
        } else {
            $booking_error = 'An error occurred while processing your booking. Please try again. Error: ' . $conn->error;
        }
    }
}

// Define SITE_NAME if not defined in config.php
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Luxury Hotel');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Book Your Stay</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <a href="rooms.php">Rooms</a> / <span>Booking</span>
                </div>
            </div>
        </section>
        
        <!-- Booking Process -->
        <section class="booking-process">
            <div class="container">
                <div class="booking-steps">
                    <div class="step <?php echo $booking_step >= 1 ? 'active' : ''; ?>">
                        <div class="step-number">1</div>
                        <div class="step-title">Select Room</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step <?php echo $booking_step >= 2 ? 'active' : ''; ?>">
                        <div class="step-number">2</div>
                        <div class="step-title">Guest Details</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step <?php echo $booking_step >= 3 ? 'active' : ''; ?>">
                        <div class="step-number">3</div>
                        <div class="step-title">Payment</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step <?php echo $booking_step >= 4 ? 'active' : ''; ?>">
                        <div class="step-number">4</div>
                        <div class="step-title">Confirmation</div>
                    </div>
                </div>
                
                <?php if ($booking_error): ?>
                    <div class="error-message">
                        <?php echo $booking_error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($booking_success): ?>
                    <!-- Step 4: Booking Confirmation -->
                    <div class="booking-confirmation">
                        <div class="confirmation-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Booking Confirmed!</h2>
                        <p>Your reservation has been successfully processed. We look forward to welcoming you to Luxury Hotel.</p>
                        
                        <div class="confirmation-details">
                            <h3>Booking Details</h3>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Booking Reference:</span>
                                    <span class="detail-value"><?php echo $booking_reference; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Room:</span>
                                    <span class="detail-value"><?php echo $room['name']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check-in:</span>
                                    <span class="detail-value"><?php echo format_date($check_in); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Check-out:</span>
                                    <span class="detail-value"><?php echo format_date($check_out); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Guests:</span>
                                    <span class="detail-value"><?php echo $guests; ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Total Amount:</span>
                                    <span class="detail-value"><?php echo format_price($total); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="confirmation-actions">
                            <a href="user/bookings.php" class="btn btn-secondary">View My Bookings</a>
                            <a href="index.php" class="btn btn-primary">Return to Homepage</a>
                        </div>
                    </div>
                <?php else: ?>
                    <form action="booking.php?room_id=<?php echo $room_id; ?>&step=<?php echo min($booking_step + 1, 4); ?>" method="post" class="booking-form">
                        <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
                        <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
                        <input type="hidden" name="guests" value="<?php echo $guests; ?>">
                        
                        <?php if ($booking_step == 1): ?>
                            <!-- Step 1: Room Selection -->
                            <div class="booking-step-content">
                                <h2>Select Your Room</h2>
                                
                                <div class="selected-room">
                                    <div class="room-image">
                                        <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>">
                                    </div>
                                    <div class="room-details">
                                        <h3><?php echo $room['name']; ?></h3>
                                        <p class="room-price">$<?php echo $room['price']; ?> per night</p>
                                        <div class="room-features">
                                            <span><i class="fas fa-user"></i> <?php echo $room['capacity']; ?> Guests</span>
                                            <span><i class="fas fa-bed"></i> <?php echo $room['bed_type']; ?></span>
                                            <span><i class="fas fa-vector-square"></i> <?php echo $room['size']; ?> m²</span>
                                        </div>
                                        <p class="room-description"><?php echo substr($room['description'], 0, 200); ?>...</p>
                                        <a href="room-details.php?id=<?php echo $room_id; ?>" class="view-details">View Full Details</a>
                                    </div>
                                </div>
                                
                                <div class="stay-details">
                                    <h3>Stay Details</h3>
                                    <div class="form-group">
                                        <label for="check_in">Check In</label>
                                        <input type="date" id="check_in" name="check_in" value="<?php echo $check_in; ?>" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="check_out">Check Out</label>
                                        <input type="date" id="check_out" name="check_out" value="<?php echo $check_out; ?>" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="guests">Guests</label>
                                        <select id="guests" name="guests" required>
                                            <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo $i == $guests ? 'selected' : ''; ?>><?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="price-summary">
                                    <h3>Price Summary</h3>
                                    <div class="summary-table">
                                        <div class="summary-row">
                                            <span>$<?php echo $room['price']; ?> x <?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></span>
                                            <span>$<?php echo $base_price; ?></span>
                                        </div>
                                        <div class="summary-row">
                                            <span>Taxes & fees (12%)</span>
                                            <span>$<?php echo $tax; ?></span>
                                        </div>
                                        <div class="summary-total">
                                            <span>Total</span>
                                            <span>$<?php echo $total; ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="booking-actions">
                                    <a href="rooms.php" class="btn btn-outline">Back to Rooms</a>
                                    <button type="submit" name="next_step" class="btn btn-primary">Continue to Guest Details</button>
                                </div>
                            </div>
                        <?php elseif ($booking_step == 2): ?>
                            <!-- Step 2: Guest Details -->
                            <div class="booking-step-content">
                                <h2>Guest Details</h2>
                                
                                <div class="guest-form">
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
                                    <div class="form-group">
                                        <label for="special_requests">Special Requests</label>
                                        <textarea id="special_requests" name="special_requests" rows="4"></textarea>
                                        <p class="form-note">Please note that special requests are subject to availability and cannot be guaranteed.</p>
                                    </div>
                                </div>
                                
                                <div class="booking-summary">
                                    <h3>Booking Summary</h3>
                                    <div class="summary-details">
                                        <div class="summary-item">
                                            <span class="item-label">Room:</span>
                                            <span class="item-value"><?php echo $room['name']; ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Check-in:</span>
                                            <span class="item-value"><?php echo format_date($check_in); ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Check-out:</span>
                                            <span class="item-value"><?php echo format_date($check_out); ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Guests:</span>
                                            <span class="item-value"><?php echo $guests; ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Total Price:</span>
                                            <span class="item-value">$<?php echo $total; ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="booking-actions">
                                    <a href="booking.php?room_id=<?php echo $room_id; ?>&step=1&check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>&guests=<?php echo $guests; ?>" class="btn btn-outline">Back</a>
                                    <button type="submit" name="next_step" class="btn btn-primary">Continue to Payment</button>
                                </div>
                            </div>
                        <?php elseif ($booking_step == 3): ?>
                            <!-- Step 3: Payment Information -->
                            <div class="booking-step-content">
                                <h2>Payment Information</h2>
                                
                                <div class="payment-options">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <div class="payment-methods">
                                            <div class="payment-method">
                                                <input type="radio" id="payment_cc" name="payment_method" value="credit_card" checked>
                                                <label for="payment_cc">
                                                    <span class="payment-icon"><i class="far fa-credit-card"></i></span>
                                                    <span>Credit Card</span>
                                                </label>
                                            </div>
                                            <div class="payment-method">
                                                <input type="radio" id="payment_paypal" name="payment_method" value="paypal">
                                                <label for="payment_paypal">
                                                    <span class="payment-icon"><i class="fab fa-paypal"></i></span>
                                                    <span>PayPal</span>
                                                </label>
                                            </div>
                                            <div class="payment-method">
                                                <input type="radio" id="payment_hotel" name="payment_method" value="pay_at_hotel">
                                                <label for="payment_hotel">
                                                    <span class="payment-icon"><i class="fas fa-hotel"></i></span>
                                                    <span>Pay at Hotel</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="credit_card_details" class="payment-details">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="card_name">Name on Card</label>
                                                <input type="text" id="card_name" name="card_name">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="card_number">Card Number</label>
                                                <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="card_expiry">Expiry Date</label>
                                                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                                            </div>
                                            <div class="form-group">
                                                <label for="card_cvv">CVV</label>
                                                <input type="text" id="card_cvv" name="card_cvv" placeholder="123">
                                            </div>
                                        </div>
                                        <p class="form-note">
                                            <i class="fas fa-lock"></i> This is a demo. No actual payment will be processed.
                                        </p>
                                    </div>
                                    
                                    <div id="paypal_details" class="payment-details" style="display: none;">
                                        <p>You will be redirected to PayPal to complete your payment after reviewing your booking.</p>
                                        <p class="form-note">
                                            <i class="fas fa-lock"></i> This is a demo. No actual payment will be processed.
                                        </p>
                                    </div>
                                    
                                    <div id="pay_at_hotel_details" class="payment-details" style="display: none;">
                                        <p>You can pay the full amount at the hotel during check-in. Please note that we may require a credit card for booking guarantee.</p>
                                        <p class="form-note">
                                            <i class="fas fa-info-circle"></i> Valid ID and credit card will be required at check-in.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="terms-conditions">
                                    <div class="form-group checkbox-group">
                                        <input type="checkbox" id="terms_agree" name="terms_agree" required>
                                        <label for="terms_agree">I agree to the <a href="#" target="_blank">terms and conditions</a>, <a href="#" target="_blank">cancellation policy</a>, and <a href="#" target="_blank">privacy policy</a>.</label>
                                    </div>
                                </div>
                                
                                <div class="booking-summary">
                                    <h3>Booking Summary</h3>
                                    <div class="summary-details">
                                        <div class="summary-item">
                                            <span class="item-label">Room:</span>
                                            <span class="item-value"><?php echo $room['name']; ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Check-in:</span>
                                            <span class="item-value"><?php echo format_date($check_in); ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Check-out:</span>
                                            <span class="item-value"><?php echo format_date($check_out); ?></span>
                                        </div>
                                        <div class="summary-item">
                                            <span class="item-label">Guests:</span>
                                            <span class="item-value"><?php echo $guests; ?></span>
                                        </div>
                                        <div class="summary-item total-item">
                                            <span class="item-label">Total Price:</span>
                                            <span class="item-value">$<?php echo $total; ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="booking-actions">
                                    <a href="booking.php?room_id=<?php echo $room_id; ?>&step=2&check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>&guests=<?php echo $guests; ?>" class="btn btn-outline">Back</a>
                                    <button type="submit" name="submit_booking" class="btn btn-primary">Confirm Booking</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        // Booking date validation
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        if (checkInInput && checkOutInput) {
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
                
                // Update booking summary
                updateBookingSummary();
            });
            
            checkOutInput.addEventListener('change', updateBookingSummary);
            
            function updateBookingSummary() {
                if (!checkInInput.value || !checkOutInput.value) return;
                
                const checkInDate = new Date(checkInInput.value);
                const checkOutDate = new Date(checkOutInput.value);
                
                // Calculate nights
                const nights = Math.floor((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                
                // Update summary
                const basePrice = <?php echo $room['price']; ?> * nights;
                const tax = basePrice * 0.12;
                const total = basePrice + tax;
                
                // Update displayed prices
                try {
                    document.querySelector('.summary-row:first-child span:first-child').textContent = 
                        `$<?php echo $room['price']; ?> x ${nights} night${nights > 1 ? 's' : ''}`;
                    document.querySelector('.summary-row:first-child span:last-child').textContent = 
                        `$${basePrice}`;
                    document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = 
                        `$${tax.toFixed(2)}`;
                    document.querySelector('.summary-total span:last-child').textContent = 
                        `$${total.toFixed(2)}`;
                } catch (e) {
                    // Elements might not exist on all steps
                }
            }
        }
        
        // Payment method toggle
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const paymentDetailsContainers = document.querySelectorAll('.payment-details');
        
        if (paymentMethods.length > 0) {
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Hide all payment details
                    paymentDetailsContainers.forEach(container => {
                        container.style.display = 'none';
                    });
                    
                    // Show the selected payment method details
                    const selectedMethodDetails = document.getElementById(`${this.value}_details`);
                    if (selectedMethodDetails) {
                        selectedMethodDetails.style.display = 'block';
                    }
                });
            });
        }
    </script>
</body>
</html>