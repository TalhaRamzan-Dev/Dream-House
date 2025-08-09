<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = getProperty($id);
if (!$property) { header('Location: dashboard.php'); exit; }

$errors = [];
$existingImages = array_column($property['images'], 'image'); // array of filenames

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'House';
    $status = $_POST['status'] ?? 'For Sale';
    $price = (float)($_POST['price'] ?? 0);
    $keep_images = $_POST['keep_images'] ?? []; // filenames

    if ($title === '') $errors[] = 'Title is required.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';

    if (empty($errors)) {
        // Save new uploaded images
        $newSaved = saveUploadedImages($_FILES['images'] ?? []);

        // Final images = kept + newly saved
        $finalImages = array_values(array_merge($keep_images, $newSaved));

        // Determine removed images (existing minus final) - delete from DB and disk
        $removed = array_diff($existingImages, $finalImages);
        if (!empty($removed)) {
            // delete rows from property_images
            global $mysqli;
            $placeholders = implode(',', array_fill(0, count($removed), '?'));
            // Prepare statement to delete each by filename and property_id
            $stmt = $mysqli->prepare("DELETE FROM property_images WHERE property_id = ? AND image = ?");
            foreach ($removed as $ri) {
                $stmt->bind_param("is", $id, $ri);
                $stmt->execute();
            }
            $stmt->close();
            // delete files
            deleteImagesFromDisk($removed);
        }

        // Insert newSaved filenames into property_images
        if (!empty($newSaved)) {
            foreach ($newSaved as $ns) insertPropertyImage($id, $ns);
        }

        // Update property fields
        $data = ['title'=>$title, 'description'=>$description, 'price'=>$price, 'type'=>$type, 'status'=>$status];
        if (updateProperty($id, $data)) {
            header('Location: dashboard.php?updated=1');
            exit;
        } else {
            $errors[] = 'Update failed.';
            // cleanup newly saved files if required
            deleteImagesFromDisk($newSaved);
        }
    }

    // reload property for UI if errors
    $property = getProperty($id);
    $existingImages = array_column($property['images'], 'image');
}

include __DIR__ . '/includes/header.php';
?>
<div class="card p-3">
  <h4>Edit Property #<?= esc($property['id']) ?></h4>
  <?php if ($errors): ?><div class="alert alert-danger"><?= esc(implode('<br>', $errors)) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-2"><input name="title" value="<?= esc($property['title']) ?>" class="form-control" required></div>
    <div class="mb-2"><textarea name="description" class="form-control" rows="4"><?= esc($property['description']) ?></textarea></div>

    <div class="row g-2">
      <div class="col"><select name="type" class="form-select"><option value="House" <?= $property['type']=='House' ? 'selected':'' ?>>House</option><option value="Land" <?= $property['type']=='Land' ? 'selected':'' ?>>Land</option></select></div>
      <div class="col"><select name="status" class="form-select"><option value="For Sale" <?= $property['status']=='For Sale' ? 'selected':'' ?>>For Sale</option><option value="For Rent" <?= $property['status']=='For Rent' ? 'selected':'' ?>>For Rent</option><option value="Sold" <?= $property['status']=='Sold' ? 'selected':'' ?>>Sold</option><option value="Rented" <?= $property['status']=='Rented' ? 'selected':'' ?>>Rented</option></select></div>
      <div class="col"><input name="price" type="number" step="0.01" value="<?= esc($property['price']) ?>" class="form-control"></div>
    </div>

    <div class="mb-3 mt-3">
      <label>Existing Images (uncheck to remove)</label>
      <div class="d-flex flex-wrap">
        <?php if (!empty($existingImages)): foreach ($existingImages as $img): ?>
          <div class="me-3 mb-2 text-center">
            <img src="../uploads/<?= esc($img) ?>" class="thumb d-block mb-1">
            <label><input type="checkbox" name="keep_images[]" value="<?= esc($img) ?>" checked> Keep</label>
          </div>
        <?php endforeach; else: ?>
          <div class="text-muted">No images yet.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-3">
      <label>Upload New Images</label>
      <input type="file" name="images[]" multiple accept="image/*" class="form-control">
      <small class="text-muted">Allowed: jpg, png, webp. Max 5MB each.</small>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="dashboard.php" class="btn btn-link">Cancel</a>
  </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
