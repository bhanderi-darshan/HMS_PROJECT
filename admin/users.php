<?php
/**
 * Users Management Page
 * This page displays all users and allows administrators to manage them.
 */
session_start();

// Include database connection
require_once 'config.php';

// Handle actions (delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = sanitize($conn, $_POST['id']);
        $deleteQuery = "DELETE FROM users WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $success_msg = "User deleted successfully.";
        } else {
            $error_msg = "Error deleting user: " . mysqli_error($conn);
        }
    }
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';

// Filter by role
$role_filter = isset($_GET['role']) ? sanitize($conn, $_GET['role']) : '';

// Build query
$query = "SELECT id, name, email, phone, role, status, created_at
          FROM users
          WHERE 1=1";

// Apply search if provided
if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}

// Apply role filter if provided
if (!empty($role_filter) && $role_filter != 'all') {
    $query .= " AND role = '$role_filter'";
}

// Count total records for pagination
$countQuery = $query;
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_num_rows($countResult);
$totalPages = ceil($totalRecords / $limit);

// Add pagination to main query
$query .= " ORDER BY created_at DESC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

// Get all roles for filter
$rolesQuery = "SELECT DISTINCT role FROM users ORDER BY role";
$rolesResult = mysqli_query($conn, $rolesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Hotel Admin</title>
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
                    <h1>Users Management</h1>
                    <div>
                        <a href="add-user.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add New User
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
                        <h3>All Users</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Search bar -->
                            <div class="search-bar">
                                <form action="" method="GET" class="d-flex">
                                    <input type="text" name="search" id="table-search" class="form-control" placeholder="Search users..." value="<?php echo $search; ?>">
                                    <button type="submit" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Role filter -->
                            <div class="filter-container">
                                <select name="role-filter" id="role-filter" class="form-control" onchange="window.location.href='users.php?role='+this.value<?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                    <option value="all" <?php echo $role_filter == '' || $role_filter == 'all' ? 'selected' : ''; ?>>All Roles</option>
                                    <?php while($role = mysqli_fetch_assoc($rolesResult)): ?>
                                    <option value="<?php echo $role['role']; ?>" <?php echo $role_filter == $role['role'] ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($role['role']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th data-sort="id">ID</th>
                                        <th data-sort="name">Name</th>
                                        <th data-sort="email">Email</th>
                                        <th data-sort="phone">Phone</th>
                                        <th data-sort="role">Role</th>
                                        <th data-sort="status">Status</th>
                                        <th data-sort="created_at">Registered On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(mysqli_num_rows($result) > 0):
                                        while($user = mysqli_fetch_assoc($result)):
                                            // Determine role badge class
                                            $roleClass = '';
                                            switch($user['role']) {
                                                case 'admin':
                                                    $roleClass = 'badge-primary';
                                                    break;
                                                case 'staff':
                                                    $roleClass = 'badge-info';
                                                    break;
                                                case 'customer':
                                                    $roleClass = 'badge-success';
                                                    break;
                                                default:
                                                    $roleClass = 'badge-secondary';
                                            }
                                            
                                            // Determine status badge class
                                            $statusClass = '';
                                            switch($user['status']) {
                                                case 'active':
                                                    $statusClass = 'badge-success';
                                                    break;
                                                case 'inactive':
                                                    $statusClass = 'badge-warning';
                                                    break;
                                                case 'suspended':
                                                    $statusClass = 'badge-danger';
                                                    break;
                                                default:
                                                    $statusClass = 'badge-secondary';
                                            }
                                    ?>
                                    <tr>
                                        <td data-column="id"><?php echo $user['id']; ?></td>
                                        <td data-column="name"><?php echo $user['name']; ?></td>
                                        <td data-column="email"><?php echo $user['email']; ?></td>
                                        <td data-column="phone"><?php echo $user['phone'] ?? 'N/A'; ?></td>
                                        <td data-column="role">
                                            <span class="badge <?php echo $roleClass; ?>"><?php echo ucfirst($user['role']); ?></span>
                                        </td>
                                        <td data-column="status">
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($user['status']); ?></span>
                                        </td>
                                        <td data-column="created_at"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="view-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-secondary" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary" data-tooltip="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $user['id']; ?>" data-name="<?php echo $user['name']; ?>" data-tooltip="Delete">
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
                                        <td colspan="8" class="text-center">No users found</td>
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
                                    <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($role_filter) ? '&role='.$role_filter : ''; ?>" class="page-link">Previous</a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($role_filter) ? '&role='.$role_filter : ''; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($page < $totalPages): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($role_filter) ? '&role='.$role_filter : ''; ?>" class="page-link">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- User Stats -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>User Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Get role counts
                            $roleStatsQuery = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
                            $roleStatsResult = mysqli_query($conn, $roleStatsQuery);
                            
                            // Get status counts
                            $statusStatsQuery = "SELECT status, COUNT(*) as count FROM users GROUP BY status";
                            $statusStatsResult = mysqli_query($conn, $statusStatsQuery);
                            
                            // Recent users count
                            $recentUsersQuery = "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                            $recentUsersResult = mysqli_query($conn, $recentUsersQuery);
                            $recentUsers = mysqli_fetch_assoc($recentUsersResult)['count'];
                            
                            while($roleStat = mysqli_fetch_assoc($roleStatsResult)):
                                $roleIcon = 'user';
                                switch($roleStat['role']) {
                                    case 'admin':
                                        $roleIcon = 'user-shield';
                                        $bgColor = 'var(--primary)';
                                        break;
                                    case 'staff':
                                        $roleIcon = 'user-tie';
                                        $bgColor = 'var(--info)';
                                        break;
                                    case 'customer':
                                        $roleIcon = 'user-tag';
                                        $bgColor = 'var(--success)';
                                        break;
                                    default:
                                        $roleIcon = 'user';
                                        $bgColor = 'var(--secondary)';
                                }
                            ?>
                            <div class="col-md-4">
                                <div class="user-stat-card">
                                    <div class="user-stat-icon" style="background-color: <?php echo $bgColor; ?>">
                                        <i class="fas fa-<?php echo $roleIcon; ?>"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <div class="user-stat-value"><?php echo $roleStat['count']; ?></div>
                                        <div class="user-stat-label"><?php echo ucfirst($roleStat['role']); ?>s</div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            
                            <div class="col-md-4">
                                <div class="user-stat-card">
                                    <div class="user-stat-icon" style="background-color: var(--warning)">
                                        <i class="fas fa-user-clock"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <div class="user-stat-value"><?php echo $recentUsers; ?></div>
                                        <div class="user-stat-label">New Users (30 days)</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            // Get total users count
                            $totalUsersQuery = "SELECT COUNT(*) as count FROM users";
                            $totalUsersResult = mysqli_query($conn, $totalUsersQuery);
                            $totalUsers = mysqli_fetch_assoc($totalUsersResult)['count'];
                            ?>
                            
                            <div class="col-md-4">
                                <div class="user-stat-card">
                                    <div class="user-stat-icon" style="background-color: var(--dark)">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <div class="user-stat-value"><?php echo $totalUsers; ?></div>
                                        <div class="user-stat-label">Total Users</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="status-chart mt-4">
                            <h4>User Status Distribution</h4>
                            <div class="status-bars">
                                <?php
                                while($statusStat = mysqli_fetch_assoc($statusStatsResult)):
                                    $percentage = ($statusStat['count'] / $totalUsers) * 100;
                                    $statusColor = 'var(--gray-500)';
                                    
                                    switch($statusStat['status']) {
                                        case 'active':
                                            $statusColor = 'var(--success)';
                                            break;
                                        case 'inactive':
                                            $statusColor = 'var(--warning)';
                                            break;
                                        case 'suspended':
                                            $statusColor = 'var(--danger)';
                                            break;
                                    }
                                ?>
                                <div class="status-item">
                                    <div class="status-label">
                                        <span class="status-name"><?php echo ucfirst($statusStat['status']); ?></span>
                                        <span class="status-count"><?php echo $statusStat['count']; ?></span>
                                    </div>
                                    <div class="status-bar-container">
                                        <div class="status-bar" style="width: <?php echo $percentage; ?>%; background-color: <?php echo $statusColor; ?>"></div>
                                    </div>
                                    <div class="status-percentage"><?php echo number_format($percentage, 1); ?>%</div>
                                </div>
                                <?php endwhile; ?>
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

.user-stat-card {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.user-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.user-stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    color: white;
    font-size: 1.5rem;
}

.user-stat-content {
    flex: 1;
}

.user-stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.1;
}

.user-stat-label {
    font-size: 0.9rem;
    color: var(--gray-600);
}

.status-chart {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-chart h4 {
    margin-top: 0;
    margin-bottom: 15px;
    color: var(--gray-800);
}

.status-bars {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.status-item {
    display: flex;
    align-items: center;
}

.status-label {
    width: 120px;
    display: flex;
    justify-content: space-between;
    margin-right: 15px;
}

.status-name {
    font-weight: 500;
    color: var(--gray-700);
}

.status-count {
    color: var(--gray-600);
    font-size: 0.9rem;
}

.status-bar-container {
    flex: 1;
    height: 10px;
    background-color: var(--gray-200);
    border-radius: 5px;
    overflow: hidden;
}

.status-bar {
    height: 100%;
    min-width: 2%;
    transition: width 1s ease-in-out;
}

.status-percentage {
    width: 60px;
    text-align: right;
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-left: 15px;
}

@keyframes barAnimation {
    from { width: 0; }
}

.status-bar {
    animation: barAnimation 1s ease-out forwards;
}
</style>