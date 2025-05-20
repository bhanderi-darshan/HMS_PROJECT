<?php
/**
 * Restaurant Menu Management Page
 * This page displays all restaurant menu items and allows administrators to manage them.
 */
session_start();

// Include database connection
require_once 'config.php';

// Handle actions (delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = sanitize($conn, $_POST['id']);
        $deleteQuery = "DELETE FROM menu_items WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $success_msg = "Menu item deleted successfully.";
        } else {
            $error_msg = "Error deleting menu item: " . mysqli_error($conn);
        }
    }
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';

// Filter by category
$category_filter = isset($_GET['category']) ? sanitize($conn, $_GET['category']) : '';

// Build query
$query = "SELECT id, name, description, price, category, is_available, dietary_info
          FROM menu_items
          WHERE 1=1";

// Apply search if provided
if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%')";
}

// Apply category filter if provided
if (!empty($category_filter) && $category_filter != 'all') {
    $query .= " AND category = '$category_filter'";
}

// Count total records for pagination
$countQuery = $query;
$countResult = mysqli_query($conn, $countQuery);
$totalRecords = mysqli_num_rows($countResult);
$totalPages = ceil($totalRecords / $limit);

// Add pagination to main query
$query .= " ORDER BY category ASC, name ASC LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

// Get all categories for filter
$categoriesQuery = "SELECT DISTINCT category FROM menu_items ORDER BY category";
$categoriesResult = mysqli_query($conn, $categoriesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu - Hotel Admin</title>
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
                    <h1>Restaurant Menu Management</h1>
                    <div>
                        <a href="add-menu-item.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Menu Item
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
                        <h3>Menu Items</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Search bar -->
                            <div class="search-bar">
                                <form action="" method="GET" class="d-flex">
                                    <input type="text" name="search" id="table-search" class="form-control" placeholder="Search menu items..." value="<?php echo $search; ?>">
                                    <button type="submit" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Category filter -->
                            <div class="filter-container">
                                <select name="category-filter" id="category-filter" class="form-control" onchange="window.location.href='restaurant.php?category='+this.value<?php echo !empty($search) ? '&search='.$search : ''; ?>">
                                    <option value="all" <?php echo $category_filter == '' || $category_filter == 'all' ? 'selected' : ''; ?>>All Categories</option>
                                    <?php while($category = mysqli_fetch_assoc($categoriesResult)): ?>
                                    <option value="<?php echo $category['category']; ?>" <?php echo $category_filter == $category['category'] ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($category['category']); ?>
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
                                        <th data-sort="category">Category</th>
                                        <th data-sort="description">Description</th>
                                        <th data-sort="price">Price</th>
                                        <th data-sort="dietary_info">Dietary Info</th>
                                        <th data-sort="is_available">Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(mysqli_num_rows($result) > 0):
                                        while($item = mysqli_fetch_assoc($result)):
                                            // Determine availability badge class
                                            $availabilityClass = $item['is_available'] ? 'badge-success' : 'badge-danger';
                                            $availabilityText = $item['is_available'] ? 'Available' : 'Unavailable';
                                    ?>
                                    <tr>
                                        <td data-column="id"><?php echo $item['id']; ?></td>
                                        <td data-column="name"><?php echo $item['name']; ?></td>
                                        <td data-column="category"><?php echo ucfirst($item['category']); ?></td>
                                        <td data-column="description" class="description-cell"><?php echo strlen($item['description']) > 60 ? substr($item['description'], 0, 60) . '...' : $item['description']; ?></td>
                                        <td data-column="price">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td data-column="dietary_info">
                                            <?php 
                                            if(!empty($item['dietary_info'])):
                                                $dietary = explode(',', $item['dietary_info']);
                                                foreach($dietary as $info):
                                                    $info = trim($info);
                                                    switch(strtolower($info)) {
                                                        case 'vegetarian':
                                                            $dietClass = 'badge-success';
                                                            break;
                                                        case 'vegan':
                                                            $dietClass = 'badge-info';
                                                            break;
                                                        case 'gluten-free':
                                                            $dietClass = 'badge-warning';
                                                            break;
                                                        default:
                                                            $dietClass = 'badge-secondary';
                                                    }
                                            ?>
                                            <span class="badge <?php echo $dietClass; ?> diet-badge"><?php echo $info; ?></span>
                                            <?php 
                                                endforeach;
                                            else:
                                                echo '-';
                                            endif;
                                            ?>
                                        </td>
                                        <td data-column="is_available">
                                            <span class="badge <?php echo $availabilityClass; ?>"><?php echo $availabilityText; ?></span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="view-menu-item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-secondary" data-tooltip="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-menu-item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary" data-tooltip="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-tooltip="Delete">
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
                                        <td colspan="8" class="text-center">No menu items found</td>
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
                                    <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($category_filter) ? '&category='.$category_filter : ''; ?>" class="page-link">Previous</a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($category_filter) ? '&category='.$category_filter : ''; ?>" class="page-link"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if($page < $totalPages): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search='.$search : ''; ?><?php echo !empty($category_filter) ? '&category='.$category_filter : ''; ?>" class="page-link">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Menu Categories -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>Menu Overview</h3>
                    </div>
                    <div class="card-body">
                        <div class="menu-categories">
                            <?php
                            // Reset the categories result pointer
                            mysqli_data_seek($categoriesResult, 0);
                            
                            // Get category counts
                            while($category = mysqli_fetch_assoc($categoriesResult)):
                                $catName = $category['category'];
                                $countQuery = "SELECT COUNT(*) as count FROM menu_items WHERE category = '$catName'";
                                $countResult = mysqli_query($conn, $countQuery);
                                $countData = mysqli_fetch_assoc($countResult);
                                
                                // Get average price
                                $avgPriceQuery = "SELECT AVG(price) as avg_price FROM menu_items WHERE category = '$catName'";
                                $avgPriceResult = mysqli_query($conn, $avgPriceQuery);
                                $avgPriceData = mysqli_fetch_assoc($avgPriceResult);
                                
                                // Determine icon based on category
                                $icon = 'utensils';
                                switch(strtolower($catName)) {
                                    case 'appetizer':
                                    case 'appetizers':
                                    case 'starters':
                                        $icon = 'cheese';
                                        break;
                                    case 'main':
                                    case 'mains':
                                    case 'entrees':
                                        $icon = 'hamburger';
                                        break;
                                    case 'dessert':
                                    case 'desserts':
                                        $icon = 'ice-cream';
                                        break;
                                    case 'beverages':
                                    case 'drinks':
                                        $icon = 'wine-glass-alt';
                                        break;
                                    case 'sides':
                                        $icon = 'drumstick-bite';
                                        break;
                                    case 'breakfast':
                                        $icon = 'egg';
                                        break;
                                }
                            ?>
                            <div class="menu-category-card">
                                <div class="menu-category-icon">
                                    <i class="fas fa-<?php echo $icon; ?>"></i>
                                </div>
                                <div class="menu-category-details">
                                    <h4><?php echo ucfirst($catName); ?></h4>
                                    <div class="menu-category-stats">
                                        <span><i class="fas fa-list"></i> <?php echo $countData['count']; ?> items</span>
                                        <span><i class="fas fa-dollar-sign"></i> Avg: $<?php echo number_format($avgPriceData['avg_price'], 2); ?></span>
                                    </div>
                                </div>
                                <a href="restaurant.php?category=<?php echo $catName; ?>" class="menu-category-link">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <?php endwhile; ?>
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

.description-cell {
    max-width: 250px;
}

.diet-badge {
    margin-right: 5px;
}

.menu-categories {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.menu-category-card {
    display: flex;
    align-items: center;
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.menu-category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.menu-category-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--primary-light);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 15px;
    flex-shrink: 0;
}

.menu-category-details {
    flex: 1;
}

.menu-category-details h4 {
    margin: 0 0 5px;
    color: var(--gray-800);
}

.menu-category-stats {
    display: flex;
    color: var(--gray-600);
    font-size: 0.9rem;
}

.menu-category-stats span {
    margin-right: 15px;
}

.menu-category-stats i {
    margin-right: 5px;
}

.menu-category-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--gray-100);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.menu-category-link:hover {
    background-color: var(--primary);
    color: white;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.menu-category-card {
    animation: fadeIn 0.5s ease-out forwards;
}

.menu-category-card:nth-child(1) {
    animation-delay: 0.1s;
}

.menu-category-card:nth-child(2) {
    animation-delay: 0.2s;
}

.menu-category-card:nth-child(3) {
    animation-delay: 0.3s;
}

.menu-category-card:nth-child(4) {
    animation-delay: 0.4s;
}

.menu-category-card:nth-child(5) {
    animation-delay: 0.5s;
}
</style>