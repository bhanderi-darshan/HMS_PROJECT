<?php
session_start();
include '../includes/config.php';

// Check if user is logged in and is an admin
// if (!is_logged_in() || !is_admin()) {
//     redirect('../login.php');
// }

// Get counts for dashboard
$sql = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$result = $conn->query($sql);
$user_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM bookings";
$result = $conn->query($sql);
$booking_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM rooms";
$result = $conn->query($sql);
$room_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM service_bookings";
$result = $conn->query($sql);
$service_booking_count = $result->fetch_assoc()['count'];

// Get recent bookings
$sql = "SELECT b.*, u.first_name, u.last_name, r.name as room_name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN rooms r ON b.room_id = r.id 
        ORDER BY b.created_at DESC 
        LIMIT 5";
$recent_bookings_result = $conn->query($sql);

// Get recent service bookings
$sql = "SELECT sb.*, u.first_name, u.last_name, s.name as service_name 
        FROM service_bookings sb 
        JOIN users u ON sb.user_id = u.id 
        JOIN services s ON sb.service_id = s.id 
        ORDER BY sb.created_at DESC 
        LIMIT 5";
$recent_service_bookings_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="admin-content">
            <!-- Top Bar -->
            <?php include 'includes/topbar.php'; ?>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="page-header">
                    <h1>Dashboard</h1>
                    <p>Welcome to the admin dashboard. Here's an overview of your hotel's performance.</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $user_count; ?></h3>
                                <p>Registered Users</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="users.php">View All Users</a>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $booking_count; ?></h3>
                                <p>Room Bookings</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="bookings.php">View All Bookings</a>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $room_count; ?></h3>
                                <p>Total Rooms</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="rooms.php">Manage Rooms</a>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $service_booking_count; ?></h3>
                                <p>Service Bookings</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-spa"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="service-bookings.php">View All Services</a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <div class="recent-section">
                    <div class="section-header">
                        <h2>Recent Room Bookings</h2>
                        <a href="bookings.php" class="view-all">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($recent_bookings_result && $recent_bookings_result->num_rows > 0) {
                                    while ($booking = $recent_bookings_result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $booking['booking_reference']; ?></td>
                                            <td><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></td>
                                            <td><?php echo $booking['room_name']; ?></td>
                                            <td><?php echo format_date($booking['check_in_date']); ?></td>
                                            <td><?php echo format_date($booking['check_out_date']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($booking['status']); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_price($booking['total_price']); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="booking-details.php?id=<?php echo $booking['id']; ?>" class="btn-icon" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit-booking.php?id=<?php echo $booking['id']; ?>" class="btn-icon" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="no-data">No bookings found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Service Bookings -->
                <div class="recent-section">
                    <div class="section-header">
                        <h2>Recent Service Bookings</h2>
                        <a href="service-bookings.php" class="view-all">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Guest</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($recent_service_bookings_result && $recent_service_bookings_result->num_rows > 0) {
                                    while ($booking = $recent_service_bookings_result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $booking['booking_reference']; ?></td>
                                            <td><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></td>
                                            <td><?php echo $booking['service_name']; ?></td>
                                            <td><?php echo format_date($booking['service_date']); ?></td>
                                            <td><?php echo $booking['service_time']; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($booking['status']); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_price($booking['total_price']); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="service-booking-details.php?id=<?php echo $booking['id']; ?>" class="btn-icon" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit-service-booking.php?id=<?php echo $booking['id']; ?>" class="btn-icon" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="no-data">No service bookings found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-header">
                        <h2>Quick Actions</h2>
                    </div>
                    <div class="actions-grid">
                        <a href="add-room.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <h3>Add New Room</h3>
                            <p>Create a new room listing</p>
                        </a>
                        <a href="add-service.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h3>Add New Service</h3>
                            <p>Create a new service offering</p>
                        </a>
                        <a href="add-user.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>Add New User</h3>
                            <p>Create user or staff account</p>
                        </a>
                        <a href="reports.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h3>View Reports</h3>
                            <p>Access booking statistics</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>