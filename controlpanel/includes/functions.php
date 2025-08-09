<?php
// admin/includes/functions.php
require_once __DIR__ . '/config.php';

/* ---------- PROPERTIES ---------- */

/**
 * Get all properties with a thumbnail (single query)
 * returns array of assoc rows; thumbnail field may be NULL
 */
function getAllProperties() {
    global $mysqli;
    $sql = "
      SELECT p.*,
             (SELECT image FROM property_images WHERE property_id = p.id LIMIT 1) AS thumbnail
      FROM properties p
      ORDER BY p.created_at DESC
    ";
    $res = $mysqli->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Get single property and its images
 */
function getProperty($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $prop = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$prop) return false;

    $stmt = $mysqli->prepare("SELECT id, image FROM property_images WHERE property_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $images = [];
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $images[] = $row; // returns id & image
    }
    $stmt->close();

    $prop['images'] = $images;
    return $prop;
}

/**
 * Insert property and return new ID
 * $data: ['title','description','type','status','price']
 * $uploadedImages: array of saved filenames
 */
function insertProperty($data, $uploadedImages = []) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO properties (title, description, price, type, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $data['title'], $data['description'], $data['price'], $data['type'], $data['status']);
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $property_id = $stmt->insert_id;
    $stmt->close();

    if (!empty($uploadedImages)) {
        $stmt = $mysqli->prepare("INSERT INTO property_images (property_id, image) VALUES (?, ?)");
        foreach ($uploadedImages as $file) {
            $stmt->bind_param("is", $property_id, $file);
            $stmt->execute();
        }
        $stmt->close();
    }

    return $property_id;
}

/**
 * Insert a single image record (used when adding images separately)
 */
function insertPropertyImage($property_id, $filename) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO property_images (property_id, image) VALUES (?, ?)");
    $stmt->bind_param("is", $property_id, $filename);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

/**
 * Update property fields; $newImages array inserted separately by caller if needed
 */
function updateProperty($id, $data) {
    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE properties SET title=?, description=?, price=?, type=?, status=? WHERE id=?");
    $stmt->bind_param("ssdssi", $data['title'], $data['description'], $data['price'], $data['type'], $data['status'], $id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

/**
 * Delete property + its images (DB cascade should remove images; also remove files)
 */
function deleteProperty($id) {
    global $mysqli;
    // get filenames
    $stmt = $mysqli->prepare("SELECT image FROM property_images WHERE property_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $files = [];
    while ($r = $res->fetch_assoc()) $files[] = $r['image'];
    $stmt->close();

    // delete files
    deleteImagesFromDisk($files);

    // delete property (ON DELETE CASCADE will remove property_images rows if FK is set)
    $stmt = $mysqli->prepare("DELETE FROM properties WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

/**
 * Delete a single image record and its file
 */
function deletePropertyImage($imageId) {
    global $mysqli;
    // get filename
    $stmt = $mysqli->prepare("SELECT image FROM property_images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) return false;
    $filename = $row['image'];

    // delete DB row
    $stmt = $mysqli->prepare("DELETE FROM property_images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $ok = $stmt->execute();
    $stmt->close();

    // delete file
    if ($ok) {
        deleteImagesFromDisk([$filename]);
    }
    return $ok;
}

/* ---------- IMAGE HANDLING ---------- */

/**
 * Save uploaded images ($_FILES['images']) and return array of filenames
 */
function saveUploadedImages($files) {
    global $uploads_dir;
    $saved = [];
    if (empty($files) || !isset($files['name'])) return $saved;

    $allowed_mime = ['image/jpeg','image/png','image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    $count = is_array($files['name']) ? count($files['name']) : 0;
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $tmp = $files['tmp_name'][$i];
        $name = $files['name'][$i];
        $size = $files['size'][$i];

        // validate mime
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        if (!in_array($mime, $allowed_mime)) continue;
        if ($size > $maxSize) continue;

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            // fallback from mime
            if ($mime === 'image/webp') $ext = 'webp'; else $ext = 'jpg';
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        $dest = rtrim($uploads_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (move_uploaded_file($tmp, $dest)) {
            $saved[] = $filename;
        }
    }
    return $saved;
}

/**
 * Delete files from disk
 */
function deleteImagesFromDisk($filenames) {
    global $uploads_dir;
    foreach ($filenames as $f) {
        if (!$f) continue;
        $path = rtrim($uploads_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $f;
        if (file_exists($path)) @unlink($path);
    }
}
