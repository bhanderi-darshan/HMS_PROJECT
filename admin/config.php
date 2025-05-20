<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luxury_hotel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log the error instead of exposing details
    error_log("Connection failed: " . $conn->connect_error);
    // Show user-friendly message
    die("Connection failed. Please try again later.");
}

// Set character set
$conn->set_charset("utf8mb4");

// Global site settings
define('SITE_NAME', 'Luxury Hotel');
define('SITE_URL', 'http://localhost/hotel');
define('ADMIN_EMAIL', 'admin@luxuryhotel.com');

// Function to clean user inputs
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if admin is logged in
function is_admin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin');
}

// Function to check if staff is logged in
function is_staff() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'staff');
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to generate a random reference number
function generate_reference() {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
}

// Function to format price
function format_price($price) {
    return '$' . number_format($price, 2);
}

// Function to format date
function format_date($date) {
    return date("F j, Y", strtotime($date));
}

// Function to get current page
function get_current_page() {
    $page = basename($_SERVER['PHP_SELF']);
    $page = str_replace('.php', '', $page);
    return $page;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Cart functions
function add_to_cart($item_id, $name, $price, $quantity = 1) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

function update_cart_quantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        } else {
            remove_from_cart($item_id);
        }
    }
}

function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

function get_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function clear_cart() {
    $_SESSION['cart'] = array();
}

// Table reservation functions
function check_table_availability($date, $time, $guests) {
    global $conn;
    
    // Get all tables that can accommodate the group
    $sql = "SELECT t.* FROM tables t 
            WHERE t.seats >= ? AND t.id NOT IN (
                SELECT tr.table_id 
                FROM table_reservations tr 
                WHERE tr.reservation_date = ? 
                AND tr.reservation_time = ? 
                AND tr.status != 'cancelled'
            )";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $guests, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function reserve_table($user_id, $table_id, $date, $time, $guests, $name, $email, $phone, $requests = '') {
    global $conn;
    
    $reference = generate_reference();
    
    $sql = "INSERT INTO table_reservations (
                user_id, table_id, reservation_reference, reservation_date, 
                reservation_time, num_guests, full_name, email, phone, 
                special_requests, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iisssissss",
        $user_id, $table_id, $reference, $date,
        $time, $guests, $name, $email, $phone,
        $requests
    );
    
    return $stmt->execute();
}
?>