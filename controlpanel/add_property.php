<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'House';
    $status = $_POST['status'] ?? 'For Sale';
    $price = (float)($_POST['price'] ?? 0);

    if ($title === '') $errors[] = 'Title is required.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';

    if (empty($errors)) {
        // Save files to disk
        $saved = saveUploadedImages($_FILES['images'] ?? []);

        $propertyId = insertProperty([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'status' => $status,
            'price' => $price
        ], $saved);

        if ($propertyId) {
            header('Location: dashboard.php?added=1');
            exit;
        } else {
            $errors[] = 'Database insert failed.';
            // cleanup
            deleteImagesFromDisk($saved);
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="card p-3">
  <h4>Add Property</h4>
  <?php if ($errors): ?><div class="alert alert-danger"><?= esc(implode('<br>', $errors)) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-2"><input name="title" class="form-control" placeholder="Title" required></div>
    <div class="mb-2"><textarea name="description" class="form-control" placeholder="Description" rows="4"></textarea></div>
    <div class="row g-2">
      <div class="col"><select name="type" class="form-select"><option>House</option><option>Land</option></select></div>
      <div class="col"><select name="status" class="form-select"><option>For Sale</option><option>For Rent</option><option>Sold</option><option>Rented</option></select></div>
      <div class="col"><input name="price" type="number" step="0.01" class="form-control" placeholder="Price" required></div>
    </div>

    <div class="mb-3 mt-3">
      <label>Images (multiple allowed)</label>
      <input type="file" name="images[]" multiple accept="image/*" class="form-control">
      <small class="text-muted">Allowed: jpg, png, webp. Max 5MB each.</small>
    </div>

    <button class="btn btn-success">Save Property</button>
    <a href="dashboard.php" class="btn btn-link">Cancel</a>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
