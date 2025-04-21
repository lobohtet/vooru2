<?php
session_start();

// Extend session lifetime to 1 month
$session_lifetime = 2592000; // 30 days in seconds
ini_set('session.gc_maxlifetime', $session_lifetime);
ini_set('session.cookie_lifetime', $session_lifetime);
session_set_cookie_params($session_lifetime);

// Check if session exists, if not, restore from cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['user_token'])) {
    $tokenParts = explode(':', base64_decode($_COOKIE['user_token']));
    if (count($tokenParts) === 2) {
        $username = $tokenParts[0];
        $hashedPassword = $tokenParts[1];

        $userFile = 'tbusersdat/' . $username . '.json';
        if (file_exists($userFile)) {
            $userData = json_decode(file_get_contents($userFile), true);
            if (hash('sha256', $userData['password']) === $hashedPassword) {
                $_SESSION['username'] = $username; // Restore session
            } else {
                setcookie('user_token', '', time() - 3600, "/"); // Invalid cookie, delete it
            }
        } else {
            setcookie('user_token', '', time() - 3600, "/"); // User does not exist, delete cookie
        }
    }
}

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>