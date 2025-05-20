<div class="admin-sidebar">
    <div class="sidebar-header">
        <h2>Luxury Hotel</h2>
        <p>Administration</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' || basename($_SERVER['PHP_SELF']) == 'booking-details.php' || basename($_SERVER['PHP_SELF']) == 'edit-booking.php' ? 'active' : ''; ?>">
                <a href="bookings.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Room Bookings</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'rooms.php' || basename($_SERVER['PHP_SELF']) == 'add-room.php' || basename($_SERVER['PHP_SELF']) == 'edit-room.php' ? 'active' : ''; ?>">
                <a href="rooms.php">
                    <i class="fas fa-bed"></i>
                    <span>Rooms</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' || basename($_SERVER['PHP_SELF']) == 'add-service.php' || basename($_SERVER['PHP_SELF']) == 'edit-service.php' ? 'active' : ''; ?>">
                <a href="services.php">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'service-bookings.php' || basename($_SERVER['PHP_SELF']) == 'service-booking-details.php' ? 'active' : ''; ?>">
                <a href="service-bookings.php">
                    <i class="fas fa-spa"></i>
                    <span>Service Bookings</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'restaurant.php' || basename($_SERVER['PHP_SELF']) == 'menu-items.php' || basename($_SERVER['PHP_SELF']) == 'table-reservations.php' ? 'active' : ''; ?>">
                <a href="restaurant.php">
                    <i class="fas fa-utensils"></i>
                    <span>Restaurant</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' || basename($_SERVER['PHP_SELF']) == 'add-user.php' || basename($_SERVER['PHP_SELF']) == 'edit-user.php' ? 'active' : ''; ?>">
                <a href="users.php">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <a href="reports.php">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <a href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <p>&copy; <?php echo date('Y'); ?> Luxury Hotel</p>
    </div>
</div>