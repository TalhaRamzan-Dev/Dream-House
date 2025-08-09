<?php
// admin/includes/auth_check.php
require_once __DIR__ . '/config.php';

// Redirect to login if not logged in
if (empty($_SESSION['admin_id'])) {
    header('Location: ../admin/login.php');
    exit;
}

// Inactivity timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_TIMEOUT)) {
    session_unset();
    session_destroy();
    header('Location: ../admin/login.php?timeout=1');
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
