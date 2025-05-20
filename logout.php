<?php
session_start();

// Unset all session variables
$_SESSION = [];
session_unset();
session_destroy();

// Delete session cookie for extra security
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: index.php");
exit();
?>
