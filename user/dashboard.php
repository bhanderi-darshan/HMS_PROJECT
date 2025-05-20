<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('../login.php');
}

// Check if user is not a regular user
if (is_admin() || is_staff()) {
    redirect('../index.php');
}

// Get user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get user's bookings
$sql = "SELECT b.*, r.name as room_name, r.image_url 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = ? 
        ORDER BY b.check_in_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();

// Get user's service bookings
$sql = "SELECT sb.*, s.name as service_name, s.image_url 
        FROM service_bookings sb 
        JOIN services s ON sb.service_id = s.id 
        WHERE sb.user_id = ? 
        ORDER BY sb.service_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$service_bookings_result = $stmt->get_result();

// Get user's table reservations
$sql = "SELECT tr.*, t.table_number, t.seats 
        FROM table_reservations tr 
        JOIN tables t ON tr.table_id = t.id 
        WHERE tr.user_id = ? 
        ORDER BY tr.reservation_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$table_reservations_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/user-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main>
        <!-- Page Banner -->
        <section class="page-banner">
            <div class="banner-content">
                <h1>My Dashboard</h1>
                <div class="breadcrumb">
                    <a href="../index.php">Home</a> / <span>My Dashboard</span>
                </div>
            </div>
        </section>
        
        <!-- Dashboard Content -->
        <section class="dashboard-section">
            <div class="container">
                <div class="dashboard-container">
                    <!-- Sidebar -->
                    <div class="dashboard-sidebar">
                        <div class="user-profile">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-info">
                                <h3><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h3>
                                <p><?php echo $user['email']; ?></p>
                            </div>
                        </div>
                        <nav class="dashboard-nav">
                            <ul>
                                <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                                <li><a href="profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                                <li><a href="change-password.php"><i class="fas fa-lock"></i> Change Password</a></li>
                                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </nav>
                    </div>
                    
                    <!-- Dashboard Content -->
                    <div class="dashboard-content">
                        <div class="welcome-message">
                            <h2>Welcome, <?php echo $user['first_name']; ?>!</h2>
                            <p>Manage your bookings, update your profile, and explore our services from your personal dashboard.</p>
                        </div>
                        
                        <!-- Dashboard Stats -->
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $bookings_result->num_rows; ?></h3>
                                    <p>Room Bookings</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-spa"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $service_bookings_result->num_rows; ?></h3>
                                    <p>Service Appointments</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $table_reservations_result->num_rows; ?></h3>
                                    <p>Table Reservations</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $bookings_result->num_rows + $service_bookings_result->num_rows + $table_reservations_result->num_rows; ?></h3>
                                    <p>Total Bookings</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upcoming Bookings -->
                        <div class="upcoming-bookings">
                            <div class="section-header">
                                <h3>Upcoming Bookings</h3>
                                <a href="bookings.php" class="view-all">View All</a>
                            </div>
                            
                            <?php
                            // Reset result pointer
                            $bookings_result->data_seek(0);
                            
                            if ($bookings_result->num_rows > 0) {
                                $count = 0;
                                echo '<div class="booking-list">';
                                
                                while ($booking = $bookings_result->fetch_assoc()) {
                                    // Only show upcoming bookings (check-in date is in the future)
                                    if (strtotime($booking['check_in_date']) >= strtotime(date('Y-m-d')) && $count < 3) {
                                        ?>
                                        <div class="booking-item">
                                            <div class="booking-image">
                                                <img src="<?php echo $booking['image_url']; ?>" alt="<?php echo $booking['room_name']; ?>">
                                            </div>
                                            <div class="booking-details">
                                                <h4><?php echo $booking['room_name']; ?></h4>
                                                <div class="booking-info">
                                                    <p><i class="fas fa-calendar-alt"></i> <?php echo format_date($booking['check_in_date']); ?> - <?php echo format_date($booking['check_out_date']); ?></p>
                                                    <p><i class="fas fa-user"></i> <?php echo $booking['num_guests']; ?> Guests</p>
                                                    <p><i class="fas fa-tag"></i> <?php echo format_price($booking['total_price']); ?></p>
                                                </div>
                                                <div class="booking-status <?php echo strtolower($booking['status']); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </div>
                                            </div>
                                            <div class="booking-actions">
                                                <a href="booking-details.php?id=<?php echo $booking['id']; ?>" class="btn btn-outline btn-sm">View Details</a>
                                            </div>
                                        </div>
                                        <?php
                                        $count++;
                                    }
                                }
                                
                                if ($count == 0) {
                                    echo '<div class="no-bookings">You have no upcoming room bookings.</div>';
                                }
                                
                                echo '</div>';
                            } else {
                                echo '<div class="no-bookings">You have no room bookings yet.</div>';
                            }
                            ?>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="quick-actions">
                            <h3>Quick Actions</h3>
                            <div class="actions-grid">
                                <a href="../booking.php" class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <h4>Book a Room</h4>
                                    <p>Reserve your stay in one of our luxurious rooms or suites.</p>
                                </a>
                                <a href="../restaurant.php#reserve-table" class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                    <h4>Reserve a Table</h4>
                                    <p>Book a table at our fine dining restaurant.</p>
                                </a>
                                <a href="../service-booking.php" class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-spa"></i>
                                    </div>
                                    <h4>Book a Service</h4>
                                    <p>Schedule spa treatments, fitness sessions, and more.</p>
                                </a>
                                <a href="../contact.php" class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <h4>Request Assistance</h4>
                                    <p>Contact our concierge for special requests or assistance.</p>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="recent-activity">
                            <h3>Recent Activity</h3>
                            <div class="activity-timeline">
                                <?php
                                // Combine all bookings for timeline
                                $activities = [];
                                
                                // Reset result pointers
                                $bookings_result->data_seek(0);
                                $service_bookings_result->data_seek(0);
                                $table_reservations_result->data_seek(0);
                                
                                // Add room bookings
                                while ($booking = $bookings_result->fetch_assoc()) {
                                    $activities[] = [
                                        'type' => 'room',
                                        'date' => $booking['created_at'],
                                        'title' => 'Room Booking: ' . $booking['room_name'],
                                        'description' => 'You booked a room for ' . format_date($booking['check_in_date']) . ' to ' . format_date($booking['check_out_date']),
                                        'icon' => 'fas fa-bed',
                                        'status' => $booking['status']
                                    ];
                                }
                                
                                // Add service bookings
                                while ($service_booking = $service_bookings_result->fetch_assoc()) {
                                    $activities[] = [
                                        'type' => 'service',
                                        'date' => $service_booking['created_at'],
                                        'title' => 'Service Booking: ' . $service_booking['service_name'],
                                        'description' => 'You booked a service for ' . format_date($service_booking['service_date']) . ' at ' . $service_booking['service_time'],
                                        'icon' => 'fas fa-spa',
                                        'status' => $service_booking['status']
                                    ];
                                }
                                
                                // Add table reservations
                                while ($table_reservation = $table_reservations_result->fetch_assoc()) {
                                    $activities[] = [
                                        'type' => 'table',
                                        'date' => $table_reservation['created_at'],
                                        'title' => 'Table Reservation: Table #' . $table_reservation['table_number'],
                                        'description' => 'You reserved a table for ' . format_date($table_reservation['reservation_date']) . ' at ' . $table_reservation['reservation_time'],
                                        'icon' => 'fas fa-utensils',
                                        'status' => $table_reservation['status']
                                    ];
                                }
                                
                                // Sort activities by date (newest first)
                                usort($activities, function($a, $b) {
                                    return strtotime($b['date']) - strtotime($a['date']);
                                });
                                
                                // Display activities
                                if (count($activities) > 0) {
                                    $count = 0;
                                    foreach ($activities as $activity) {
                                        if ($count < 5) { // Show only the 5 most recent activities
                                            ?>
                                            <div class="activity-item">
                                                <div class="activity-icon <?php echo $activity['type']; ?>">
                                                    <i class="<?php echo $activity['icon']; ?>"></i>
                                                </div>
                                                <div class="activity-content">
                                                    <div class="activity-header">
                                                        <h4><?php echo $activity['title']; ?></h4>
                                                        <span class="activity-date"><?php echo date('M j, Y', strtotime($activity['date'])); ?></span>
                                                    </div>
                                                    <p><?php echo $activity['description']; ?></p>
                                                    <div class="activity-status <?php echo strtolower($activity['status']); ?>">
                                                        <?php echo ucfirst($activity['status']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $count++;
                                        }
                                    }
                                } else {
                                    echo '<div class="no-activity">No recent activity to display.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include '../includes/footer.php'; ?>
    
    <script src="../js/main.js"></script>
</body>
</html>