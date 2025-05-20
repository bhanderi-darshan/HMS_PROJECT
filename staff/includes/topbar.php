<div class="staff-topbar">
    <button class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="topbar-right">
        <div class="notifications">
            <button class="notification-toggle">
                <i class="far fa-bell"></i>
                <span class="notification-count">2</span>
            </button>
            <div class="notification-dropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <a href="#" class="mark-all-read">Mark all as read</a>
                </div>
                <div class="notification-list">
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New spa appointment booked for today</p>
                            <p class="notification-time">30 minutes ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">Appointment status updated to confirmed</p>
                            <p class="notification-time">2 hours ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New guest registered</p>
                            <p class="notification-time">Yesterday</p>
                        </div>
                    </a>
                </div>
                <div class="notification-footer">
                    <a href="notifications.php">View all notifications</a>
                </div>
            </div>
        </div>
        
        <div class="user-menu">
            <button class="user-menu-toggle">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="user-dropdown">
                <a href="profile.php">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>