<?php
// Global config bootstrap
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_config.php';

// Simple auth helpers
function current_user() { 
    return isset($_SESSION['user']) ? $_SESSION['user'] : null; 
}

function require_login() { 
    if (!current_user()) { 
        header('Location: login.php'); 
        exit; 
    } 
}

function require_owner() { 
    $u = current_user(); 
    if (!$u || $u['user_type'] !== 'owner') { 
        header('Location: login.php'); 
        exit; 
    } 
}

function require_admin() {
    $u = current_user();
    if (!$u || $u['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
}

function e($s) { 
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>