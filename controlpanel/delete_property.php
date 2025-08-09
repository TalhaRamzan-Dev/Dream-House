<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: dashboard.php'); exit; }

// get property to ensure exists and get images
$prop = getProperty($id);
if ($prop) {
    // gather filenames
    $filenames = array_column($prop['images'], 'image');
    // delete files
    if (!empty($filenames)) deleteImagesFromDisk($filenames);
    // delete property (and images via FK/CASCADE)
    deleteProperty($id);
}

header('Location: dashboard.php?deleted=1');
exit;
