<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['sold','rented'])) {
    header('Location: dashboard.php'); exit;
}

$newStatus = $action === 'sold' ? 'Sold' : 'Rented';
$prop = getProperty($id);
if ($prop) {
    $data = [
        'title'=>$prop['title'],
        'description'=>$prop['description'],
        'price'=>$prop['price'],
        'type'=>$prop['type'],
        'status'=>$newStatus
    ];
    updateProperty($id, $data);
}

header('Location: dashboard.php');
exit;
