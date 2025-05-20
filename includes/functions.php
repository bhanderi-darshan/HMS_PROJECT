<?php
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function is_staff() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function clean_input($data) {
    return htmlspecialchars(trim($data));
}
