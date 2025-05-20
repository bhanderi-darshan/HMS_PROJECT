<div class="staff-sidebar">
    <div class="sidebar-header">
        <h2>Luxury Hotel</h2>
        <p>Staff Portal</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' || basename($_SERVER['PHP_SELF']) == 'appointment-details.php' ? 'active' : ''; ?>">
                <a href="appointments.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-appointment.php' ? 'active' : ''; ?>">
                <a href="add-appointment.php">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Appointment</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <a href="services.php">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'guests.php' ? 'active' : ''; ?>">
                <a href="guests.php">
                    <i class="fas fa-users"></i>
                    <span>Guests</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                <a href="reports.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php">
                    <i class="fas fa-user-edit"></i>
                    <span>My Profile</span>
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