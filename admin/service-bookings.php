<?php
/**
 * Service Bookings Management Page
 * This page displays all service bookings and allows administrators to manage them.
 */
session_start();

// Include database connection
require_once 'config.php';

// Handle actions (delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = sanitize($conn, $_POST['id']);
        $deleteQuery = "DELETE FROM service_bookings WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $success_msg = "Service booking deleted successfully.";
        } else {
            $error_msg = "Error deleting service booking: " . mysqli_error($conn);
        }
    }
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';

// Filter by status
$status_filter = isset($_GET['status']) ? sanitize($conn, $_GET['status']) : '';

// Build query
$query = "SELECT sb.id, u.name as user_name, u.id as user_id, s.name as service_name, s.id as service_id, 
                 sb.booking_date, sb.service_time, sb.quantity, sb.status, sb.total_price, sb.created_at
          FROM service_bookings sb
          JOIN users u ON sb.user_id = u.id
          JOIN services s ON sb.service_id = s.id
          WHERE 1=1";

// Apply search if provided
if (!empty($search)) {
    $query .= " AND (u.name LIKE '%$search%' OR s.name LIKE '%$search%' OR sb.id LIKE '%$search%')";
}

// Apply status filter if provided
if (!empty($status_filter) && $status_filter != 'all') {
    $query .= " AND sb.status = '$status_filter'";
}

// Count total records for pagination
$countQuery = $query;
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_num_rows($countResult);
$totalPages = ceil($totalRecords / $limit);

// Add pagination to main query
$query .= " ORDER BY sb.booking_date DESC, sb.service_time ASC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Bookings - Hotel Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php require_once 'sidebar.php'; ?>
        
        <div class="main-content">
            <!-- Top Bar -->
            <?php require_once 'topbar.php'; ?>
            
            <div class="page-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Service Bookings Management</h1>
                    <div>
                        <a href="add-service-booking.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Service Booking
                        </a>
                    </div>
                </div>
                
                <?php if(isset($success_msg)): ?>
                <div class="alert alert-success">
                    <?php echo $success_msg; ?>
                </div>
                <?php endif; ?>
                
                <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_msg; ?>
                </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h3>All Service Bookings</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Search bar -->
                            <div class="search-bar">
                                <form action="" method="GET" class="d-flex">
                                    <input type="text" name="search" id="table-search" class="form-control" placeholder="Search bookings..." value="<?php echo $search; ?>">
                                    <button type="submit" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Status filter -->
                            <div class="filter-container">
                                <select name="status-filter" id="status-filter" class="form-control" onchange="window.location.href='service-bookings.php?status='+this.value<?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                    <option value="all" <?php echo $status_filter == '' || $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th data-sort="id">ID</th>
                                        <th data-sort="user">Guest</th>
                                        <th data-sort="service">Service</th>
                                        <th data-sort="date">Date</th>
                                        <th data-sort="time">Time</th>
                                        <th data-sort="quantity">Qty</th>
                                        <th data-sort="status">Status</th>
                                        <th data-sort="price">Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(mysqli_num_rows($result) > 0):
                                        while($booking = mysqli_fetch_assoc($result)):
                                            // Determine badge class based on status
                                            $statusClass = '';
                                            switch($booking['status']) {
                                                case 'confirmed':
                                                    $statusClass = 'badge-success';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'badge-warning';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'badge-info';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'badge-danger';
                                                    break;
                                                default:
                                                    $statusClass = 'badge-secondary';
                                            }
                                    ?>
                                    <tr>
                                        <td data-column="id"><?php echo $booking['id']; ?></td>
                                        <td data-column="user">
                                            <a href="view-user.php?id=<?php echo $booking['user_id']; ?>" class="user-link">
                                                <?php echo $booking['user_name']; ?>
                                            </a>
                                        </td>
                                        <td data-column="service">
                                            <a href="view-service.php?id=<?php echo $booking['service_id']; ?>" class="service-link">
                                                <?php echo $booking['service_name']; ?>
                                            </a>
                                        </td>
                                        <td data-column="date"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                        <td data-column="time"><?php echo date('h:i A', strtotime($booking['service_time'])); ?></td>
                                        <td data-column="quantity"><?php echo $booking['quantity']; ?></td>
                                        <td data-column="status">
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($booking['status']); ?></span>
                                        </td>
                                        <td data-column="price">$<?php echo number_format($booking['total_price'], 2); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="view-service-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-secondary" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-service-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary" data-tooltip="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $booking['id']; ?>" data-name="Service Booking #<?php echo $booking['id']; ?>" data-tooltip="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No service bookings found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($totalPages > 1): ?>
                        <div class="pagination-container mt-3">
                            <ul class="pagination">
                                <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($status_filter) ? '&status='.$status_filter : ''; ?>" class="page-link">Previous</a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($status_filter) ? '&status='.$status_filter : ''; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($page < $totalPages): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($status_filter) ? '&status='.$status_filter : ''; ?>" class="page-link">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Today's Bookings Quick View -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>Today's Service Bookings</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $todayDate = date('Y-m-d');
                        $todayQuery = "SELECT sb.id, u.name as user_name, s.name as service_name, sb.service_time, sb.status
                                      FROM service_bookings sb
                                      JOIN users u ON sb.user_id = u.id
                                      JOIN services s ON sb.service_id = s.id
                                      WHERE sb.booking_date = '$todayDate'
                                      ORDER BY sb.service_time ASC";
                        $todayResult = mysqli_query($conn, $todayQuery);
                        
                        if(mysqli_num_rows($todayResult) > 0):
                        ?>
                        <div class="timeline">
                            <?php while($todayBooking = mysqli_fetch_assoc($todayResult)): ?>
                            <div class="timeline-item">
                                <div class="timeline-time">
                                    <span><?php echo date('h:i A', strtotime($todayBooking['service_time'])); ?></span>
                                </div>
                                <div class="timeline-content">
                                    <h4><?php echo $todayBooking['service_name']; ?></h4>
                                    <p>Guest: <?php echo $todayBooking['user_name']; ?></p>
                                    <div class="timeline-status">
                                        <?php
                                        // Determine badge class based on status
                                        $timelineStatusClass = '';
                                        switch($todayBooking['status']) {
                                            case 'confirmed':
                                                $timelineStatusClass = 'badge-success';
                                                break;
                                            case 'pending':
                                                $timelineStatusClass = 'badge-warning';
                                                break;
                                            case 'completed':
                                                $timelineStatusClass = 'badge-info';
                                                break;
                                            case 'cancelled':
                                                $timelineStatusClass = 'badge-danger';
                                                break;
                                            default:
                                                $timelineStatusClass = 'badge-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $timelineStatusClass; ?>"><?php echo ucfirst($todayBooking['status']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center p-3">
                            <p>No service bookings scheduled for today.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Delete</h4>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="deleteItemName"></span>?</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDelete">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
    
    <script src="assets/js/dashboard.js"></script>
</body>
</html>

<style>
.search-bar {
    max-width: 300px;
}

.filter-container {
    width: 200px;
}

.user-link, .service-link {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s;
}

.user-link:hover, .service-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 100px;
    width: 2px;
    background: var(--gray-200);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    display: flex;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-time {
    width: 100px;
    padding-right: 20px;
    text-align: right;
    font-weight: 600;
    color: var(--primary);
}

.timeline-content {
    flex: 1;
    background: white;
    padding: 15px;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    margin-left: 20px;
}

.timeline-content:before {
    content: '';
    position: absolute;
    top: 15px;
    left: -28px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--primary);
    border: 3px solid white;
    box-shadow: 0 0 0 2px var(--primary-light);
}

.timeline-content h4 {
    margin: 0 0 5px;
    color: var(--gray-800);
}

.timeline-content p {
    margin: 0 0 10px;
    color: var(--gray-600);
}

.timeline-status {
    display: flex;
    justify-content: flex-end;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.timeline-item {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.timeline-item:nth-child(1) {
    animation-delay: 0.1s;
}

.timeline-item:nth-child(2) {
    animation-delay: 0.2s;
}

.timeline-item:nth-child(3) {
    animation-delay: 0.3s;
}

.timeline-item:nth-child(4) {
    animation-delay: 0.4s;
}

.timeline-item:nth-child(5) {
    animation-delay: 0.5s;
}
</style>