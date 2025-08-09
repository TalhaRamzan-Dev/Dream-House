<?php
// Fetch properties with optional filters
// Fetch properties with optional filters
function getProperties($conn, $type = '', $status = '') {
    $query = "SELECT * FROM properties WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($type)) {
        $query .= " AND type = ?";
        $params[] = $type;
        $types .= "s";
    }

    if (!empty($status)) {
        $query .= " AND status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $query .= " ORDER BY created_at DESC";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("SQL prepare error: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}
//Get Random Image for HomeScreen

// Fetch a random image for a property
function getRandomPropertyImage($conn, $property_id) {
    $stmt = $conn->prepare("SELECT image FROM property_images WHERE property_id = ? ORDER BY RAND() LIMIT 1");
    if (!$stmt) {
        die("SQL error in getRandomPropertyImage(): " . $conn->error);
    }

    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['image'];
    }

    $stmt->close();
    return null;
}

// Fetch single property by ID
function getPropertyById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
    if (!$stmt) {
        die("SQL error in getPropertyById(): " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fetch multiple images for a property
function getPropertyImages($conn, $property_id) {
    $stmt = $conn->prepare("SELECT image FROM property_images WHERE property_id = ?");
    if (!$stmt) {
        die("SQL error in getPropertyImages(): " . $conn->error);
    }

    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['image'];  // <-- Corrected here
    }

    $stmt->close();
    return $images;
}

// Format price
function formatPrice($price) {
    return "$" . number_format($price, 0);
}
?>
