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
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        // Check if user exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                // Check if there's a redirect URL stored in session
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect_url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    redirect($redirect_url);
                } else {
                    // Redirect based on role
                    if ($user['role'] == 'admin') {
                        redirect('admin/index.php');
                    } elseif ($user['role'] == 'staff') {
                        redirect('staff/index.php');
                    } else {
                        redirect('user/dashboard.php');
                    }
                }
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'User not found';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
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
                <h1>Login</h1>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Login</span>
                </div>
            </div>
        </section>
        
        <!-- Login Form -->
        <section class="auth-section">
            <div class="container">
                <div class="auth-container">
                    <div class="auth-form">
                        <h2>Welcome Back</h2>
                        
                        <?php if ($message): ?>
                            <div class="info-message">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="error-message">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="login.php" method="post">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="password-input">
                                    <input type="password" id="password" name="password" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group remember-forgot">
                                <div class="remember-me">
                                    <input type="checkbox" id="remember" name="remember">
                                    <label for="remember">Remember me</label>
                                </div>
                                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                        
                        <div class="auth-separator">
                            <span>or</span>
                        </div>
                        
                        <div class="auth-options">
                            <p>Don't have an account? <a href="register.php">Register now</a></p>
                        </div>
                    </div>
                    <div class="auth-image">
                        <img src="https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Exterior">
                        <div class="auth-overlay">
                            <div class="overlay-content">
                                <h3>Experience Luxury</h3>
                                <p>Login to access your account and manage your bookings with ease.</p>
                                <ul class="auth-benefits">
                                    <li><i class="fas fa-check-circle"></i> View and manage your reservations</li>
                                    <li><i class="fas fa-check-circle"></i> Access exclusive member offers</li>
                                    <li><i class="fas fa-check-circle"></i> Earn rewards with every stay</li>
                                    <li><i class="fas fa-check-circle"></i> Faster booking process</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div class="profile-dropdown">
    <a href="user/profile.php">My Profile</a>
    <a href="user/bookings.php">My Bookings</a>
    <a href="login.php?action=logout">Logout</a>
</div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle icon
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>