<header class="main-header">
    <div class="container">
        <div class="logo">
            <a href="index.php">
                <h1>Luxury Hotel</h1>
            </a>
        </div>
        <nav class="main-nav">
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo (get_current_page() == 'index') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="about.php" class="<?php echo (get_current_page() == 'about') ? 'active' : ''; ?>">About</a></li>
                <li><a href="rooms.php" class="<?php echo (get_current_page() == 'rooms' || get_current_page() == 'room-details') ? 'active' : ''; ?>">Rooms</a></li>
                <li><a href="restaurant.php" class="<?php echo (get_current_page() == 'restaurant') ? 'active' : ''; ?>">Restaurant</a></li>
                <li><a href="services.php" class="<?php echo (get_current_page() == 'services' || get_current_page() == 'service-details') ? 'active' : ''; ?>">Services</a></li>
                <li><a href="gallery.php" class="<?php echo (get_current_page() == 'gallery') ? 'active' : ''; ?>">Gallery</a></li>
                <li><a href="contact.php" class="<?php echo (get_current_page() == 'contact') ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <a href="booking.php" class="btn btn-primary">Book Now</a>
            <div class="user-menu">
                <?php if (is_logged_in()): ?>
                    <button class="user-menu-toggle">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <ul class="user-dropdown">
                        <?php if (is_admin()): ?>
                            <li><a href="admin/index.php">Admin Dashboard</a></li>
                        <?php elseif (is_staff()): ?>
                            <li><a href="staff/index.php">Staff Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="user/dashboard.php">My Account</a></li>
                            <li><a href="user/bookings.php">My Bookings</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                <?php else: ?>
                    <button class="user-menu-toggle">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <ul class="user-dropdown">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>