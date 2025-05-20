<?php
/**
 * Rooms Management Page
 * This page displays all rooms and allows administrators to manage them.
 */
session_start();

// Include database connection
require_once 'config.php';

// Handle actions (delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = sanitize($conn, $_POST['id']);
        $deleteQuery = "DELETE FROM rooms WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $success_msg = "Room deleted successfully.";
        } else {
            $error_msg = "Error deleting room: " . mysqli_error($conn);
        }
    }
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';

// Filter by type
$type_filter = isset($_GET['type']) ? sanitize($conn, $_GET['type']) : '';

// Build query
$query = "SELECT id, room_number, type, price, max_occupancy, is_available 
          FROM rooms 
          WHERE 1=1";

// Apply search if provided
if (!empty($search)) {
    $query .= " AND (room_number LIKE '%$search%' OR type LIKE '%$search%' OR id LIKE '%$search%')";
}

// Apply type filter if provided
if (!empty($type_filter) && $type_filter != 'all') {
    $query .= " AND type = '$type_filter'";
}

// Count total records for pagination
$countQuery = $query;
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_num_rows($countResult);
$totalPages = ceil($totalRecords / $limit);

// Add pagination to main query
$query .= " ORDER BY room_number ASC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Admin</title>
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
                    <h1>Rooms Management</h1>
                    <div>
                        <a href="add-room.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Room
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
                        <h3>All Rooms</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Search bar -->
                            <div class="search-bar">
                                <form action="" method="GET" class="d-flex">
                                    <input type="text" name="search" id="table-search" class="form-control" placeholder="Search rooms..." value="<?php echo $search; ?>">
                                    <button type="submit" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Type filter -->
                            <div class="filter-container">
                                <select name="type-filter" id="status-filter" class="form-control" onchange="window.location.href='rooms.php?type='+this.value<?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                    <option value="all" <?php echo $type_filter == '' || $type_filter == 'all' ? 'selected' : ''; ?>>All Types</option>
                                    <option value="standard" <?php echo $type_filter == 'standard' ? 'selected' : ''; ?>>Standard</option>
                                    <option value="deluxe" <?php echo $type_filter == 'deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                                    <option value="suite" <?php echo $type_filter == 'suite' ? 'selected' : ''; ?>>Suite</option>
                                    <option value="executive" <?php echo $type_filter == 'executive' ? 'selected' : ''; ?>>Executive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th data-sort="id">ID</th>
                                        <th data-sort="room_number">Room Number</th>
                                        <th data-sort="type">Type</th>
                                        <th data-sort="price">Price</th>
                                        <th data-sort="max_occupancy">Max Occupancy</th>
                                        <th data-sort="is_available">Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(mysqli_num_rows($result) > 0):
                                        while($room = mysqli_fetch_assoc($result)):
                                            // Determine availability badge class
                                            $availabilityClass = $room['is_available'] ? 'badge-success' : 'badge-danger';
                                            $availabilityText = $room['is_available'] ? 'Available' : 'Occupied';
                                    ?>
                                    <tr>
                                        <td data-column="id"><?php echo $room['id']; ?></td>
                                        <td data-column="room_number"><?php echo $room['room_number']; ?></td>
                                        <td data-column="type"><?php echo ucfirst($room['type']); ?></td>
                                        <td data-column="price">$<?php echo number_format($room['price'], 2); ?></td>
                                        <td data-column="max_occupancy"><?php echo $room['max_occupancy']; ?> persons</td>
                                        <td data-column="is_available">
                                            <span class="badge <?php echo $availabilityClass; ?>"><?php echo $availabilityText; ?></span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="view-room.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-secondary" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-room.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-primary" data-tooltip="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $room['id']; ?>" data-name="Room <?php echo $room['room_number']; ?>" data-tooltip="Delete">
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
                                        <td colspan="7" class="text-center">No rooms found</td>
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
                                    <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($type_filter) ? '&type='.$type_filter : ''; ?>" class="page-link">Previous</a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($type_filter) ? '&type='.$type_filter : ''; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($page < $totalPages): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($type_filter) ? '&type='.$type_filter : ''; ?>" class="page-link">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Room Type Statistics -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>Room Overview</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Get room type counts
                            $typesQuery = "SELECT type, COUNT(*) as count FROM rooms GROUP BY type";
                            $typesResult = mysqli_query($conn, $typesQuery);
                            
                            // Get availability counts
                            $availabilityQuery = "SELECT is_available, COUNT(*) as count FROM rooms GROUP BY is_available";
                            $availabilityResult = mysqli_query($conn, $availabilityQuery);
                            $availabilityCounts = [];
                            while($row = mysqli_fetch_assoc($availabilityResult)) {
                                $availabilityCounts[$row['is_available']] = $row['count'];
                            }
                            
                            $availableCount = $availabilityCounts[1] ?? 0;
                            $occupiedCount = $availabilityCounts[0] ?? 0;
                            
                            while($type = mysqli_fetch_assoc($typesResult)):
                            ?>
                            <div class="col-md-4">
                                <div class="stat-card">
                                    <div class="stat-card-header">
                                        <h4><?php echo ucfirst($type['type']); ?> Rooms</h4>
                                    </div>
                                    <div class="stat-card-body">
                                        <div class="stat-card-value"><?php echo $type['count']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            
                            <div class="col-md-4">
                                <div class="stat-card available">
                                    <div class="stat-card-header">
                                        <h4>Available</h4>
                                    </div>
                                    <div class="stat-card-body">
                                        <div class="stat-card-value"><?php echo $availableCount; ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="stat-card occupied">
                                    <div class="stat-card-header">
                                        <h4>Occupied</h4>
                                    </div>
                                    <div class="stat-card-body">
                                        <div class="stat-card-value"><?php echo $occupiedCount; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

.stat-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    margin-bottom: 15px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.stat-card-header h4 {
    color: var(--gray-700);
    margin-bottom: 10px;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary);
}

.stat-card.available .stat-card-value {
    color: var(--success);
}

.stat-card.occupied .stat-card-value {
    color: var(--danger);
}
</style>