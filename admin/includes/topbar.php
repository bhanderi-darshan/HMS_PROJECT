<?php
/**
 * Top Navigation Bar Component
 * This file contains the top navigation bar for the admin dashboard.
 */
?>
<header class="topbar">
    <div class="topbar-left">
        <button id="toggle-sidebar" class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="search-container">
            <form action="#" method="GET">
                <input type="text" placeholder="Search..." class="search-input">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="topbar-right">
        <div class="topbar-item">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="badge notification-badge">3</span>
            </button>
            
            <div class="dropdown-menu notification-menu">
                <div class="dropdown-header">
                    <h6>Notifications</h6>
                    <a href="#" class="mark-all">Mark all as read</a>
                </div>
                
                <div class="dropdown-body">
                    <a href="#" class="dropdown-item unread">
                        <div class="item-icon bg-primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="item-content">
                            <h6>New booking received</h6>
                            <p>Room 203 booked for tomorrow</p>
                            <span class="item-time">3 mins ago</span>
                        </div>
                    </a>
                    
                    <a href="#" class="dropdown-item unread">
                        <div class="item-icon bg-info">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="item-content">
                            <h6>New user registered</h6>
                            <p>User John Doe created an account</p>
                            <span class="item-time">30 mins ago</span>
                        </div>
                    </a>
                    
                    <a href="#" class="dropdown-item">
                        <div class="item-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="item-content">
                            <h6>Task completed</h6>
                            <p>Monthly report generated</p>
                            <span class="item-time">Yesterday</span>
                        </div>
                    </a>
                </div>
                
                <div class="dropdown-footer">
                    <a href="#">View all notifications</a>
                </div>
            </div>
        </div>
        
        <div class="topbar-item">
            <button class="user-profile-btn">
                <img src="assets/img/user-avatar.jpg" alt="Admin User" class="avatar-sm">
                <span class="user-name"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            <div class="dropdown-menu profile-menu">
                <a href="profile.php" class="dropdown-item">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="settings.php" class="dropdown-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<style>
.topbar {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 60px;
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 1.5rem;
    z-index: 999;
    transition: left 0.3s ease;
}

.topbar-left {
    display: flex;
    align-items: center;
}

.menu-toggle {
    background: none;
    border: none;
    color: var(--gray-600);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    margin-right: 1rem;
    display: none;
}

.menu-toggle:hover {
    color: var(--primary);
}

.search-container {
    position: relative;
}

.search-input {
    background-color: var(--gray-100);
    border: none;
    border-radius: 20px;
    padding: 0.5rem 1rem;
    padding-right: 2.5rem;
    width: 250px;
    font-size: 0.9rem;
}

.search-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(15, 82, 186, 0.2);
}

.search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-500);
    cursor: pointer;
}

.topbar-right {
    display: flex;
    align-items: center;
}

.topbar-item {
    position: relative;
    margin-left: 1.5rem;
}

.notification-btn {
    background: none;
    border: none;
    color: var(--gray-600);
    font-size: 1.1rem;
    cursor: pointer;
    position: relative;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: var(--danger);
    color: white;
    font-size: 0.7rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-profile-btn {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
}

.user-name {
    font-size: 0.9rem;
    color: var(--gray-700);
    margin-right: 5px;
}

.dropdown-menu {
    position: absolute;
    top: 45px;
    right: 0;
    background-color: white;
    border-radius: 4px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 280px;
    overflow: hidden;
    display: none;
    z-index: 1000;
}

.topbar-item:hover .dropdown-menu {
    display: block;
    animation: fadeIn 0.2s ease-in-out;
}

.dropdown-header {
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--gray-200);
}

.dropdown-header h6 {
    margin: 0;
    font-size: 0.9rem;
}

.mark-all {
    font-size: 0.8rem;
    color: var(--primary);
    text-decoration: none;
}

.dropdown-body {
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    display: flex;
    padding: 15px;
    border-bottom: 1px solid var(--gray-200);
    text-decoration: none;
    color: var(--gray-700);
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: var(--gray-100);
}

.dropdown-item.unread {
    background-color: rgba(15, 82, 186, 0.05);
}

.item-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.bg-primary {
    background-color: var(--primary);
}

.bg-info {
    background-color: var(--info);
}

.bg-success {
    background-color: var(--success);
}

.item-content {
    flex: 1;
}

.item-content h6 {
    margin: 0 0 5px;
    font-size: 0.9rem;
}

.item-content p {
    margin: 0;
    font-size: 0.8rem;
    color: var(--gray-600);
}

.item-time {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.dropdown-footer {
    padding: 10px;
    text-align: center;
    border-top: 1px solid var(--gray-200);
}

.dropdown-footer a {
    font-size: 0.8rem;
    color: var(--primary);
    text-decoration: none;
}

.dropdown-divider {
    height: 1px;
    background-color: var(--gray-200);
    margin: 0;
}

.profile-menu {
    width: 180px;
}

.profile-menu .dropdown-item {
    padding: 10px 15px;
}

.profile-menu .dropdown-item i {
    margin-right: 10px;
    width: 16px;
    text-align: center;
}

@media (max-width: 992px) {
    .topbar {
        left: 0;
    }
    
    .menu-toggle {
        display: block;
    }
    
    .search-input {
        width: 180px;
    }
}

@media (max-width: 576px) {
    .search-container {
        display: none;
    }
    
    .user-name {
        display: none;
    }
}
</style>