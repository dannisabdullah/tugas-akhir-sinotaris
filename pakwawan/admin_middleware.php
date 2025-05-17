<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAdminAccess() {
    if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: log.php');
        exit;
    }
}
