<?php
/**
 * Bookings Management Page
 * This page displays all bookings and allows administrators to manage them.
 */
session_start();

// Include database connection
require_once 'config.php';

// Handle actions (delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = sanitize($conn, $_POST['id']);
        $deleteQuery = "DELETE FROM bookings WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $success_msg = "Booking deleted successfully.";
        } else {
            $error_msg = "Error deleting booking: " . mysqli_error($conn);
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
$query = "SELECT b.id, u.name as user_name, r.room_number, r.type, b.check_in_date, b.check_out_date, b.status, b.total_price, b.created_at
          FROM bookings b
          JOIN users u ON b.user_id = u.id
          JOIN rooms r ON b.room_id = r.id
          WHERE 1=1";

// Apply search if provided
if (!empty($search)) {
    $query .= " AND (u.name LIKE '%$search%' OR r.room_number LIKE '%$search%' OR b.id LIKE '%$search%')";
}

// Apply status filter if provided
if (!empty($status_filter) && $status_filter != 'all') {
    $query .= " AND b.status = '$status_filter'";
}

// Count total records for pagination
$countQuery = $query;
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_num_rows($countResult);
$totalPages = ceil($totalRecords / $limit);

// Add pagination to main query
$query .= " ORDER BY b.created_at DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - Hotel Admin</title>
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
                    <h1>Bookings Management</h1>
                    <div>
                        <a href="add-booking.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Booking
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
                        <h3>All Bookings</h3>
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
                                <select name="status-filter" id="status-filter" class="form-control" onchange="window.location.href='bookings.php?status='+this.value<?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                    <option value="all" <?php echo $status_filter == '' || $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
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
                                        <th data-sort="room">Room</th>
                                        <th data-sort="check_in">Check In</th>
                                        <th data-sort="check_out">Check Out</th>
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
                                                case 'cancelled':
                                                    $statusClass = 'badge-danger';
                                                    break;
                                                default:
                                                    $statusClass = 'badge-info';
                                            }
                                    ?>
                                    <tr>
                                        <td data-column="id"><?php echo $booking['id']; ?></td>
                                        <td data-column="user"><?php echo $booking['user_name']; ?></td>
                                        <td data-column="room"><?php echo $booking['room_number'] . ' (' . $booking['type'] . ')'; ?></td>
                                        <td data-column="check_in"><?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?></td>
                                        <td data-column="check_out"><?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?></td>
                                        <td data-column="status">
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($booking['status']); ?></span>
                                        </td>
                                        <td data-column="price">$<?php echo number_format($booking['total_price'], 2); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-secondary" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary" data-tooltip="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $booking['id']; ?>" data-name="Booking #<?php echo $booking['id']; ?>" data-tooltip="Delete">
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
                                        <td colspan="8" class="text-center">No bookings found</td>
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

.search-bar form {
    display: flex;
}

.search-bar .form-control {
    flex: 1;
    margin-right: 10px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.pagination-container {
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 5px;
}

.page-link {
    display: block;
    padding: 8px 12px;
    background-color: white;
    border: 1px solid var(--gray-300);
    color: var(--primary);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s;
}

.page-item.active .page-link {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-link:hover {
    background-color: var(--gray-100);
}

.modal-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1050;
    justify-content: center;
    align-items: center;
}
</style>