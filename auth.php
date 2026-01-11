<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}

function login($userId, $username) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
}

function logout() {
    session_unset();
    session_destroy();
}
?>