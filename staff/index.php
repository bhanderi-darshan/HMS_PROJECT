<?php
session_start();
include '../includes/config.php';

// Check if user is logged in and is a staff member
if (!is_logged_in() || !is_staff()) {
    redirect('../login.php');
}

// Get counts for dashboard
$sql = "SELECT COUNT(*) as count FROM service_bookings";
$result = $conn->query($sql);
$service_booking_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM service_bookings WHERE status = 'pending'";
$result = $conn->query($sql);
$pending_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM service_bookings WHERE status = 'confirmed'";
$result = $conn->query($sql);
$confirmed_count = $result->fetch_assoc()['count'];

$sql = "SELECT COUNT(*) as count FROM service_bookings WHERE status = 'completed'";
$result = $conn->query($sql);
$completed_count = $result->fetch_assoc()['count'];

// Get today's appointments
$today = date('Y-m-d');
$sql = "SELECT sb.*, u.first_name, u.last_name, s.name as service_name 
        FROM service_bookings sb 
        JOIN users u ON sb.user_id = u.id 
        JOIN services s ON sb.service_id = s.id 
        WHERE sb.service_date = ? 
        ORDER BY sb.service_time ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$today_appointments = $stmt->get_result();

// Get upcoming appointments
$sql = "SELECT sb.*, u.first_name, u.last_name, s.name as service_name 
        FROM service_bookings sb 
        JOIN users u ON sb.user_id = u.id 
        JOIN services s ON sb.service_id = s.id 
        WHERE sb.service_date > ? 
        ORDER BY sb.service_date ASC, sb.service_time ASC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$upcoming_appointments = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="staff-container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="staff-content">
            <!-- Top Bar -->
            <?php include 'includes/topbar.php'; ?>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="page-header">
                    <h1>Staff Dashboard</h1>
                    <p>Welcome to the staff dashboard. Manage service appointments and guest requests.</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $service_booking_count; ?></h3>
                                <p>Total Appointments</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $pending_count; ?></h3>
                                <p>Pending Appointments</p>
                            </div>
                            <div class="stat-card-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $confirmed_count; ?></h3>
                                <p>Confirmed Appointments</p>
                            </div>
                            <div class="stat-card-icon confirmed">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3><?php echo $completed_count; ?></h3>
                                <p>Completed Appointments</p>
                            </div>
                            <div class="stat-card-icon completed">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Appointments -->
                <div class="appointments-section">
                    <div class="section-header">
                        <h2>Today's Appointments</h2>
                        <div class="date-display">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo date('l, F j, Y'); ?></span>
                        </div>
                    </div>
                    
                    <?php if ($today_appointments->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="staff-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Guest</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($appointment = $today_appointments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo date('h:i A', strtotime($appointment['service_time'])); ?></td>
                                            <td><?php echo $appointment['service_name']; ?></td>
                                            <td><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($appointment['status']); ?>">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="appointment-details.php?id=<?php echo $appointment['id']; ?>" class="btn-icon" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="update-status.php?id=<?php echo $appointment['id']; ?>&status=completed" class="btn-icon complete" title="Mark as Completed">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-appointments">
                            <i class="far fa-calendar-check"></i>
                            <p>No appointments scheduled for today.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Upcoming Appointments -->
                <div class="appointments-section">
                    <div class="section-header">
                        <h2>Upcoming Appointments</h2>
                        <a href="appointments.php" class="view-all">View All</a>
                    </div>
                    
                    <?php if ($upcoming_appointments->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="staff-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Guest</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($appointment = $upcoming_appointments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo format_date($appointment['service_date']); ?></td>
                                            <td><?php echo date('h:i A', strtotime($appointment['service_time'])); ?></td>
                                            <td><?php echo $appointment['service_name']; ?></td>
                                            <td><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo strtolower($appointment['status']); ?>">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="appointment-details.php?id=<?php echo $appointment['id']; ?>" class="btn-icon" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($appointment['status'] == 'pending'): ?>
                                                        <a href="update-status.php?id=<?php echo $appointment['id']; ?>&status=confirmed" class="btn-icon confirm" title="Confirm Appointment">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-appointments">
                            <i class="far fa-calendar-check"></i>
                            <p>No upcoming appointments scheduled.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-header">
                        <h2>Quick Actions</h2>
                    </div>
                    <div class="actions-grid">
                        <a href="appointments.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h3>View All Appointments</h3>
                            <p>See all scheduled appointments</p>
                        </a>
                        <a href="add-appointment.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <h3>Add New Appointment</h3>
                            <p>Schedule a new service appointment</p>
                        </a>
                        <a href="services.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h3>Manage Services</h3>
                            <p>View and update service details</p>
                        </a>
                        <a href="reports.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Service Reports</h3>
                            <p>View service booking statistics</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/staff.js"></script>
</body>
</html>