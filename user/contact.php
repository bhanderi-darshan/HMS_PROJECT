<?php
session_start();
include 'includes/config.php';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $subject = clean_input($_POST['subject']);
    $message = clean_input($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address';
    } else {
        // In a real application, this would send an email or insert into a database
        // For this demo, we'll just display a success message
        $success_message = 'Thank you for your message. We will get back to you soon!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Contact Us</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Contact</span>
                </div>
            </div>
        </section>
        
        <!-- Contact Information -->
        <section class="contact-info">
            <div class="container">
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>123 Luxury Lane<br>Prestige City, PC 12345<br>United States</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Phone Numbers</h3>
                        <p>Reservations: +1 (555) 123-4567<br>Front Desk: +1 (555) 123-4568<br>Customer Service: +1 (555) 123-4569</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Addresses</h3>
                        <p>Reservations: bookings@luxuryhotel.com<br>Information: info@luxuryhotel.com<br>Support: help@luxuryhotel.com</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <h3>Working Hours</h3>
                        <p>Front Desk: 24/7<br>Reservations: 8:00 AM - 10:00 PM<br>Customer Service: 9:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact Form & Map -->
        <section class="contact-form-map">
            <div class="container">
                <div class="contact-container">
                    <div class="contact-form">
                        <h2>Send Us a Message</h2>
                        
                        <?php if ($success_message): ?>
                            <div class="success-message">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="error-message">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="contact.php" method="post" id="contactForm">
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit_contact" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                    <div class="contact-map">
                        <h2>Find Us</h2>
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215180275523!2d-73.98773!3d40.757920000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1643559719882!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Frequently Asked Questions -->
        <section class="faqs">
            <div class="container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>What are your check-in and check-out times?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Check-in time is 3:00 PM and check-out time is 12:00 PM. Early check-in and late check-out may be available upon request, subject to availability and additional charges may apply.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Is breakfast included in the room rate?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Breakfast is included in select room packages. Please check your specific reservation details or contact our reservation team to add a breakfast package to your stay.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Do you have parking facilities?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, we offer both valet parking and self-parking options. Valet parking is available for $40 per night with in and out privileges. Self-parking is available for $30 per night.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Is there a cancellation fee?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Our standard cancellation policy requires notice 24 hours prior to arrival to avoid a cancellation fee equal to one night's stay. Special rates and promotions may have different cancellation policies, which will be specified in your reservation details.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Do you have accessible rooms for guests with disabilities?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, we offer accessible rooms and facilities for guests with disabilities. Please contact our reservations team to ensure an accessible room is reserved for your stay.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Is Wi-Fi available at the hotel?</h3>
                            <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, complimentary high-speed Wi-Fi is available throughout the hotel, including all guest rooms and public areas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Get in Touch CTA -->
        <section class="get-in-touch">
            <div class="container">
                <div class="touch-content">
                    <h2>Need Immediate Assistance?</h2>
                    <p>For urgent inquiries or special arrangements, contact our dedicated customer service team directly.</p>
                    <div class="contact-buttons">
                        <a href="tel:+15551234567" class="btn btn-primary"><i class="fas fa-phone-alt"></i> Call Us Now</a>
                        <a href="mailto:vip@luxuryhotel.com" class="btn btn-secondary"><i class="fas fa-envelope"></i> Email VIP Service</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script src="js/contact.js"></script>
</body>
</html>