<?php
session_start();
include 'includes/config.php';

// Check if user is already logged in
if (is_logged_in()) {
    // Redirect based on role
    if (is_admin()) {
        redirect('admin/index.php');
    } elseif (is_staff()) {
        redirect('staff/index.php');
    } else {
        redirect('user/dashboard.php');
    }
}

$error = '';
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = clean_input($_POST['phone']);
    $role = clean_input($_POST['role']);
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif ($role !== 'user' && $role !== 'staff') {
        $error = 'Invalid role selected';
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email address already in use';
        } else {
            // Hash password
            $password = $_POST['password'];

            
            // Insert new user
            $sql = "INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $hashed_password, $phone, $role);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'An error occurred during registration. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>Register</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Register</span>
                </div>
            </div>
        </section>
        
        <!-- Registration Form -->
        <section class="auth-section">
            <div class="container">
                <div class="auth-container">
                    <div class="auth-form">
                        <h2>Create an Account</h2>
                        
                        <?php if ($success): ?>
                            <div class="success-message">
                                <?php echo $success; ?>
                                <p><a href="login.php">Click here to login</a></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="error-message">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="register.php" method="post">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="password-input">
                                    <input type="password" id="password" name="password" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-meter">
                                        <div class="strength-bar"></div>
                                    </div>
                                    <span class="strength-text">Password strength</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="password-input">
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Account Type</label>
                                <div class="role-selection">
                                    <div class="role-option">
                                        <input type="radio" id="role_user" name="role" value="user" checked>
                                        <label for="role_user">
                                            <i class="fas fa-user"></i>
                                            <span>Guest</span>
                                            <p>Book rooms and services</p>
                                        </label>
                                    </div>
                                    <div class="role-option">
                                        <input type="radio" id="role_staff" name="role" value="staff">
                                        <label for="role_staff">
                                            <i class="fas fa-user-tie"></i>
                                            <span>Staff</span>
                                            <p>Manage hotel services</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group checkbox-group">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a></label>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="register" class="btn btn-primary btn-block">Create Account</button>
                            </div>
                        </form>
                        
                        <div class="auth-separator">
                            <span>or</span>
                        </div>
                        
                        <div class="auth-options">
                            <p>Already have an account? <a href="login.php">Login</a></p>
                        </div>
                    </div>
                    <div class="auth-image">
                        <img src="https://images.pexels.com/photos/2029719/pexels-photo-2029719.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Interior">
                        <div class="auth-overlay">
                            <div class="overlay-content">
                                <h3>Join Our Community</h3>
                                <p>Create an account to enjoy exclusive benefits and a personalized experience.</p>
                                <ul class="auth-benefits">
                                    <li><i class="fas fa-check-circle"></i> Exclusive member rates</li>
                                    <li><i class="fas fa-check-circle"></i> Faster booking process</li>
                                    <li><i class="fas fa-check-circle"></i> Special offers and promotions</li>
                                    <li><i class="fas fa-check-circle"></i> Manage your reservations online</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Toggle icon
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            });
            
            // Password strength meter
            const passwordInput = document.getElementById('password');
            const strengthBar = document.querySelector('.strength-bar');
            const strengthText = document.querySelector('.strength-text');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Length check
                if (password.length >= 8) {
                    strength += 25;
                }
                
                // Uppercase check
                if (/[A-Z]/.test(password)) {
                    strength += 25;
                }
                
                // Number check
                if (/[0-9]/.test(password)) {
                    strength += 25;
                }
                
                // Special character check
                if (/[^A-Za-z0-9]/.test(password)) {
                    strength += 25;
                }
                
                // Update strength bar
                strengthBar.style.width = strength + '%';
                
                // Update color based on strength
                if (strength <= 25) {
                    strengthBar.style.backgroundColor = '#e63946';
                    strengthText.textContent = 'Weak';
                } else if (strength <= 50) {
                    strengthBar.style.backgroundColor = '#e9c46a';
                    strengthText.textContent = 'Fair';
                } else if (strength <= 75) {
                    strengthBar.style.backgroundColor = '#2a9d8f';
                    strengthText.textContent = 'Good';
                } else {
                    strengthBar.style.backgroundColor = '#2a9d8f';
                    strengthText.textContent = 'Strong';
                }
            });
            
            // Password confirmation validation
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Form validation
            const registerForm = document.querySelector('form');
            const termsCheckbox = document.getElementById('terms');
            
            registerForm.addEventListener('submit', function(event) {
                if (!termsCheckbox.checked) {
                    event.preventDefault();
                    alert('Please agree to the Terms of Service and Privacy Policy');
                }
            });
        });
    </script>
</body>
</html>